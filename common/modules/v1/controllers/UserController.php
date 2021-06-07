<?php

namespace common\modules\v10\controllers;

use Yii;
use common\models\Teachers;
use common\components\JwtAuth;
use common\models\TeacherClasses;
use common\models\TeacherSettings;
use common\models\TeacherPushNotifications;
use common\modules\v10\models\ChangePasswordForm;

/**
 * User Controller API
 *
 * @author trungnb
 */
class UserController extends \common\controllers\ApiController
{
    /**
     * get user info
     * @param int $teacher_id
     * @return array $data
     */
    private function getUserInfo($teacher_id)
    {
        $teacher = Teachers::findOne(['id' => $teacher_id, 'status' => ACTIVE]);
        // sanitize user data before return to client
        if (isset($teacher->password)) {
            unset($teacher->password);
        }
        if (isset($teacher->is_temporary_password)) {
            unset($teacher->is_temporary_password);
        }
        if (isset($teacher->temp_password)) {
            unset($teacher->temp_password);
        }

        $data = $teacher->toArray();
        if ($teacher->photo) {
            $data['photo'] = $teacher->photo->toArray();
            $data['photo']['url'] = Yii::$app->gcs->getSignedUrl($teacher->photo);
        } else {
            $data['photo'] = null;
        }

        // classes
        $teacher_classes = TeacherClasses::findAll(['teacher_id' => $teacher_id, 'status' => ACTIVE]);
        $classes = [];
        foreach ($teacher_classes as $tc) {
            // strip inactive classes, schoolGrade, school
            if ($tc->class->status !== ACTIVE) continue;
            if ($tc->class->schoolGrade->status !== ACTIVE) continue;
            if ($tc->class->schoolGrade->school->status !== ACTIVE) continue;

            $class_data = $tc->class->toArray();
            $class_data['school_grade'] = $tc->class->schoolGrade->toArray();
            $class_data['school'] = $tc->class->schoolGrade->school->toArray();

            $classes[] = $class_data;
        }

        // settings
        $settings = TeacherSettings::findAll(['teacher_id' => $teacher_id, 'status' => ACTIVE]);

        return [$data, $classes, $settings];
    }

    /**
     * get profile
     */
    public function actionProfile()
    {
        $teacher_id = Yii::$app->user->identity->id;

        list($data, $classes, $settings) = $this->getUserInfo($teacher_id);

        if (empty($classes)) {
            throw new \yii\web\BadRequestHttpException();
        }

        return [
            "name" => "get user profile",
            "code" => 0,
            "message" => "profile success",
            "status" => 200,
            "user" => $data,
            "classes" => $classes,
            "settings" => $settings,
        ];
    }

    /**
     * upload photo base64
     * @param string $filename
     * @param string $filetype
     * @param string $base64
     * @param int $class_id
     * @param int $kid_id
     */
    public function actionUploadPhotoBase64()
    {
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $filename = $request->post('filename');
        $filetype = $request->post('filetype');
        $base64 = $request->post('base64');

        if (!$filetype || !$base64) {
            throw new \yii\web\BadRequestHttpException;
        }

        // handle empty filename
        Yii::info('upload photo with filename ' . $filename . 'filetype ' . $filetype);
        if (!$filename) {
            $ext = '.jpg'; // default
            list($type, $ext) = explode('/', $filetype);
            if ($ext == 'jpeg') {
                $ext = 'jpg';
            }
            $filename = 'photo_' . date('YmdHis') . '.' . $ext;
            Yii::info('generate filename ' . $filename);
        }

        // validation
        $teacher_id = Yii::$app->user->identity->id;
        $bucket = getenv('GCP_BUCKET_PRIVATE');
        $filepath = 'teachers';
        $data = [
            'teacher_id' => $teacher_id,
        ];

        if ($upload = Yii::$app->gcs->uploadBase64($bucket, $filepath, $filename, $base64, $data)) {
            return $upload;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }

    /**
     * update avatar
     * @param int $photo_id
     */
    public function actionUpdateAvatar()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $photo_id = $request->post('photo_id');
        $photo = \common\models\Photos::findOne($photo_id);
        if (!$photo) {
            throw new \yii\web\BadRequestHttpException;
        }

        $teacher = Teachers::findOne($teacher_id);
        $teacher->photo_id = $photo_id;


        if ($teacher->save()) {
            $teacher = Teachers::findOne(['id' => $teacher_id, 'status' => ACTIVE]);
            $data = $teacher->toArray();
            $data['photo'] = $teacher->photo->toArray();
            $data['photo']['url'] = Yii::$app->gcs->getSignedUrl($teacher->photo);

            return [
                "name" => "update avatar",
                "code" => 0,
                "message" => "success",
                "status" => 200,
                "user" => $data,
            ];
        } else {
            return $teacher->errors;
        }
    }

    /**
     * update profile
     * @param string $first_name
     * @param string $last_name
     * @param int $gender
     * @param string $address
     * @param int $yob
     */
    public function actionEditProfile()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;

        $teacher = Teachers::findOne($teacher_id);
        $teacher->first_name = $request->post('first_name') ? $request->post('first_name') : $teacher->first_name;
        $teacher->last_name = $request->post('last_name') ? $request->post('last_name') : $teacher->last_name;
        $teacher->gender = $request->post('gender') ? $request->post('gender') : $teacher->gender;
        $teacher->address = $request->post('address') ? $request->post('address') : $teacher->address;
        $teacher->dob = $request->post('dob') ? $request->post('dob') : $teacher->dob;

        if ($teacher->save()) {
            list($data, $classes, $settings) = $this->getUserInfo($teacher_id);
            return [
                "name" => "update profile",
                "code" => 0,
                "message" => "update profile success",
                "status" => 200,
                "user" => $data,
                "classes" => $classes,
                "settings" => $settings,
            ];
        } else {
            return $teacher->errors;
        }
    }

    /** 
     * user settings
     * @param string $option
     * @param string $value
     */
    public function actionSettings()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;

        $option = $request->post('option');
        $value = $request->post('value');
        $settings = TeacherSettings::findOne(['teacher_id' => $teacher_id, 'option' => $option]);

        if (!$settings) {
            $settings = new TeacherSettings();
            $settings->teacher_id = $teacher_id;
            $settings->option = $option;
            $settings->value = $value;
            $settings->status = ACTIVE;
            if ($settings->save()) {
                list($data, $classes, $settings) = $this->getUserInfo($teacher_id);

                return [
                    "name" => "user settings",
                    "code" => 0,
                    "message" => "success",
                    "status" => 200,
                    "user" => $data,
                    "classes" => $classes,
                    "settings" => $settings,
                ];
            } else {
                return $settings->errors;
            }
        } else {
            // update
            $settings->value = $value;
            $settings->status = ACTIVE;

            if ($settings->save()) {
                list($data, $classes, $settings) = $this->getUserInfo($teacher_id);

                return [
                    "name" => "user settings",
                    "code" => 0,
                    "message" => "success",
                    "status" => 200,
                    "user" => $data,
                    "classes" => $classes,
                    "settings" => $settings,
                ];
            } else {
                return $settings->errors;
            }
        }
    }

    /**
     * get contacts
     * @param int $class_id
     */
    public function actionContacts()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;

        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);

        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException();
        }

        $kid_classes = \common\models\KidClasses::findAll(['class_id' => $class_id, 'status' => ACTIVE]);
        $kid_ids = [];
        foreach ($kid_classes as $kc) {
            $kid_ids[] = $kc->kid_id;
        }

        $kids = \common\models\Kids::find()->where(['in', 'id', $kid_ids])->andWhere(['status' => ACTIVE])->orderBy('first_name ASC')->all();

        $contacts = [];
        // TODO: hide teacher contacts
        /*
        foreach ($kids as $kid) {
            $data = $kid->toArray();
            if ($kid->photo) {
                $data['photo'] = $kid->photo->toArray();
                $data['photo']['url'] = Yii::$app->gcs->getSignedUrl($kid->photo);
            } else {
                $data['photo'] = null;
            }

            $data['parents'] = [];
            foreach ($kid->parentKids as $parent_kid) {
                $data_parent = $parent_kid->parent->toArray();
                if ($parent_kid->parent->photo) {
                    $data_parent['photo'] = $parent_kid->parent->photo->toArray();
                    $data_parent['photo']['url'] = Yii::$app->gcs->getSignedUrl($parent_kid->parent->photo);
                } else {
                    $data_parent['photo'] = null;
                }
                $data['parents'][] = $data_parent;
            }

            $contacts[] = $data;
        }
        */

        return [
            "name" => "user contacts",
            "code" => 0,
            "message" => "success",
            "status" => 200,
            "data" => $contacts,
        ];
    }

    /**
     * get photo singned url
     * @param int $photo_id
     */
    public function actionPhotoSignedUrl()
    {
        $request = Yii::$app->request;
        $parent_id = Yii::$app->user->identity->id;

        $photo_id = $request->post('photo_id');
        $photo = \common\models\Photos::findOne(['id' => $photo_id, 'status' => ACTIVE]);

        if (!$photo) {
            throw new \yii\web\BadRequestHttpException();
        }

        $data = $photo->toArray();
        $data['url'] = Yii::$app->gcs->getSignedUrl($photo);

        return [
            "name" => "photo signed url",
            "code" => 0,
            "message" => "success",
            "status" => 200,
            "data" => $data,
        ];
    }
    public function actionListNotification()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $page = $request->post('page', 1);

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        // Get all notification by teacher_id
        $notification = TeacherPushNotifications::find()
            ->where(['teacher_id' => $teacher_id])
            ->andWhere('push_data IS NOT NULL')
            ->andWhere([
                'or',
                ['class_id'=>$class_id],
                ['class_id'=>null,'push_type'=>TeacherPushNotifications::PUSH_TYPE_TEACHERABSENCE]
            ])
            ->limit(TeacherPushNotifications::PAGE_SIZE)
            ->offset(($page - 1) * TeacherPushNotifications::PAGE_SIZE)
            ->orderBy('created_at DESC')
            ->all();

        $data = [];
        foreach ($notification as $key => $nt) {
            $noti['notification_id'] = $nt->id;
            $noti['content'] = $nt->push_content;
            $noti['status'] = $nt->status;
            $noti['data'] = json_decode($nt->push_data);
            $noti['time'] = $nt->created_at;
            $data[] = $noti;
        }
        return [
            'name' => 'get notification teacher succes',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $data,
        ];
    }

    public function actionReadNotification()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $notification_id = $request->post('notification_id');

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $notification = TeacherPushNotifications::findOne($notification_id);

        if (!$notification || $notification->teacher_id !== $teacher_id) {
            throw new \yii\web\BadRequestHttpException;
        }
        $notification->status = TeacherPushNotifications::READ;

        if ($notification->save()) {
            return [
                'name' => 'read notification success',
                'code' => 0,
                'message' => 'success',
                'status' => 200,
            ];
        }
    }

    public function actionCountUnreadNotification()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $notificationCount = TeacherPushNotifications::find()
            ->where([
                'status' => TeacherPushNotifications::UNREAD,
                'class_id' => $class_id,
                'teacher_id' => $teacher_id
            ])
            ->andWhere('push_data IS NOT NULL')
            ->count();

        return [
            'name' => 'count notification success',
            'code' => 0,
            'count' => $notificationCount,
            'message' => 'success',
            'status' => 200,
        ];
    }

    /**
     * change password
     * @param string $password
     * @param string $confirm_password
     * @throws \yii\web\BadRequestHttpException if invalid parameters
     * @return status 200 if change password success
     */
    public function actionChangePassword()
    {
        $request = Yii::$app->request;

        if (
            !$request->post('password')
            && !$request->post('confirm_password')
        ) {
            throw new \yii\web\BadRequestHttpException('invalid parameters');
        }

        $password = $request->post('password');
        $confirm_password = $request->post('confirm_password');

        $form = new ChangePasswordForm();
        if ($form->load($request->post(), '') && $form->validate()) {
            $user_id = Yii::$app->user->identity->id;
            $user = Teachers::findOne(['id' => $user_id, 'status' => ACTIVE]);
            $user->setPassword($password);
            $user->is_temporary_password = Teachers::ISNOT_TEMP_PASSWORD;

            if ($user->save()) {
                return [
                    'name' => 'change password',
                    'code' => 0,
                    'message' => 'success',
                    'status' => 200,
                ];
            } else {
                return [
                    'name' => 'change password',
                    'code' => -2,
                    'message' => 'failed',
                    'status' => 502,
                    'error' => $user->errors,
                ];
            }
        } else {
            return [
                'name' => 'change password',
                'code' => -1,
                'message' => 'failed',
                'status' => 501,
                'error' => $form->errors,
            ];
        }
    }


    public function actionCheckAppVersion()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
    
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $app_id = $request->post('app_id');
        $app_version = \common\models\AppVersions::find()
            ->where(['app_id' => $app_id, 'status' => ACTIVE])
            ->one();

        return [
            'name' => 'check app version success',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $app_version
        ];
    }
}
