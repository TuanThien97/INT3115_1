<?php

namespace common\modules\v10\modules\absence\controllers;

use Yii;
use common\models\KidAbsences;
use common\models\TeacherClasses;
use yii\rest\Controller;
use common\models\ParentPushNotifications;
use common\models\TeacherPushNotifications;
use common\models\PrincipalPushNotifications;

/**
 * Default controller for the `absence` module
 */
class DefaultController extends \common\controllers\ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * list absence application by date
     */
    public function actionSubmissions()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;

        // paging
        $page = $request->post('page', 1);
        $to_date = $request->post('to_date');
        $from_date = $request->post('from_date');
        $class_id = $request->post('class_id');
        // conditions
        $query = KidAbsences::find()
            ->where(['status' => KidAbsences::SUBMISSION, 'class_id' => $class_id]);

        if ($to_date) {
            $query->andWhere(['<=', 'date(created_at)', $to_date]);
        }
        if ($from_date) {
            $query->andWhere(['>=', 'date(created_at)', $from_date]);
        }

        $query->orderBy('id desc')
            ->limit(KidAbsences::PAGE_SIZE)
            ->offset(($page - 1) * KidAbsences::PAGE_SIZE);

        $submissions = $query->all();

        // count total
        $total_query = KidAbsences::find()
            ->where(['status' => KidAbsences::SUBMISSION, 'class_id' => $class_id]);

        if ($to_date) {
            $total_query->andWhere(['<=', 'date(created_at)', $to_date]);
        }
        if ($from_date) {
            $total_query->andWhere(['>=', 'date(created_at)', $from_date]);
        }

        $total = $total_query->count();

        $data = [];
        foreach ($submissions as $submit) {
            $submission_data = $submit->toArray();
            $submission_data['kid'] = $submit->kid->toArray();
            if ($submit->kid->photo) {
                $photo = $submit->kid->photo;
                $submission_data['kid']['photo'] = $photo->toArray();
                $submission_data['kid']['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo);
            } else {
                $submission_data['kid']['photo'] = null;
            }

            $submission_data['class'] = $submit->class;
            $submission_data['absence_reason'] = $submit->absenceReason;
            $data[] = $submission_data;
        }

        return [
            'name' => 'list absence submission',
            'code' => 0,
            'status' => 200,
            'message' => 'get list success',
            'page' => $page,
            'total' => $total,
            'data' => $data
        ];
    }
    /**
     * get today
     * 
     */
    public function actionGetAbsenceToday()
    {

        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $date_input = new \DateTime();
        $date = $request->post('date', $date_input->format('Y-m-d'));;
        $data = [];
        $data['kid_absence_today'] = 0;
        $data['absence_submission'] = 0;
        $data['absence_approval'] = 0;

        $kid_absence_today = KidAbsences::find()
            ->where(['<=', 'DATE(from_date)', $date])
            ->andWhere(['>=', 'DATE(to_date)', $date])
            ->andWhere(['!=', 'status', INACTIVE])
            ->andWhere(['class_id' => $class_id])
            ->count();

        $kid_checkin_absence_today = \common\models\KidCheckins::find()
            ->where(['DATE(day)' => $date, 'class_id' => $class_id])
            ->andWhere(['!=', 'status', INACTIVE])
            ->andWhere(['!=', 'status', \common\models\KidCheckins::PRESENCE])
            ->count();
        //  print_r($kid_absence_today);die;
        $kid_absence_submitsion = KidAbsences::find()
            ->where(['class_id' => $class_id, 'DATE(created_at)' => $date, 'status' => KidAbsences::SUBMISSION])
            ->count();
        $kid_absence_aprove = KidAbsences::find()
            ->where(['class_id' => $class_id, 'DATE(created_at)' => $date, 'status' => KidAbsences::APPROVAL])
            ->count();
        $data['kid_absence_today'] = $kid_checkin_absence_today;
        $data['absence_submission'] = $kid_absence_submitsion;
        $data['absence_approval'] = $kid_absence_aprove;
        return [
            'name' => 'get data absence today',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data,
        ];
    }

    /**
     * get list kid aabsen today
     */
    public function actionGetListKidAbsenceToday()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        //$day=$request->post('day');
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $date_input = new \DateTime();
        $date = $request->post('date', $date_input->format('Y-m-d'));;
        $data = [];

        $kid_absences = \common\models\KidCheckins::find()
            ->where(['DATE(day)' => $date, 'class_id' => $class_id])
            ->andWhere(['!=', 'status', INACTIVE])
            ->andWhere(['!=', 'status', \common\models\KidCheckins::PRESENCE])
            ->all();
        foreach ($kid_absences as $kc) {
            $data_kid = $kc->kid->toArray();
            $data_kid['absence'] = $kc->toArray();
            if ($kc->kid->photo) {
                $data_kid['photo'] = $kc->kid->photo->toArray();
                $data_kid['photo']['url'] = Yii::$app->gcs->getSignedUrl($kc->kid->photo);
            } else {
                $data_kid['photo'] = null;
            }

            $data[] = $data_kid;
        }
        return [
            'name' => 'get list absence',
            'code' => 0,
            'message' => 'list kids success',
            'status' => 200,
            'data' => $data
        ];
    }

    /**
     * detail absence submission
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $kid_absence_id = $request->post('kid_absence_id', 0);
        $kid_absence = KidAbsences::findOne($kid_absence_id);
        if (!$kid_absence) {
            throw new \yii\web\NotFoundHttpException;
        }

        // validate
        $classes = TeacherClasses::findAll(['teacher_id' => $teacher_id, 'status' => ACTIVE]);
        $class_ids = [];
        foreach ($classes as $cl) {
            $class_ids[] = $cl->class_id;
        }
        if (!in_array($kid_absence->class_id, $class_ids)) {
            throw new \yii\web\ForbiddenHttpException;
        }

        $parent_kids = \common\models\ParentKids::findAll(['kid_id' => $kid_absence->kid_id, 'status' => ACTIVE]);

        $data = $kid_absence->toArray();
        $data['kid'] = $kid_absence->kid->toArray();
        if ($kid_absence->kid->photo) {
            $data['kid']['photo'] = $kid_absence->kid->photo->toArray();
            $data['kid']['photo']['url'] = Yii::$app->gcs->getSignedURL($kid_absence->kid->photo);
        }

        //$data['parent'] = $kid_absence->kid->parent;
        $data['parent'] = null;
        foreach ($parent_kids as $parent_kid) {
            $data['parent'] = $parent_kid->parent->toArray();
            if ($parent_kid->parent->photo) {
                $photo = $parent_kid->parent->photo;
                $data['parent']['photo'] = $photo->toArray();
                $data['parent']['photo']['url'] = Yii::$app->gcs->getSignedURL($photo);
            }
            break;
        }
        $data['class'] = $kid_absence->class;
        $data['absence_reason'] = $kid_absence->absenceReason;

        return [
            'name' => 'detail absence submission',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data,
        ];
    }
    /**
     * approve submission
     */
    public function actionApprove()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $kid_absence_id = $request->post('kid_absence_id', 0);
        $kid_absence = KidAbsences::findOne($kid_absence_id);
        if (!$kid_absence) {
            throw new \yii\web\NotFoundHttpException;
        }
        // validate
        $classes = TeacherClasses::findAll(['teacher_id' => $teacher_id, 'status' => ACTIVE]);
        $class_ids = [];
        foreach ($classes as $cl) {
            $class_ids[] = $cl->class_id;
        }
        if (!in_array($kid_absence->class_id, $class_ids)) {
            throw new \yii\web\ForbiddenHttpException;
        }

        if ($kid_absence->status == KidAbsences::APPROVAL) {
            return [
                'name' => 'approve absence submission',
                'code' => 1,
                'status' => 200,
                'message' => 'this submission has been approved already'
            ];
        }

        $kid_absence->teacher_id = $teacher_id;
        $kid_absence->status = KidAbsences::APPROVAL;
        if ($kid_absence->save()) {
            // send notification
            $kid_name = $kid_absence->kid->first_name . ' ' . $kid_absence->kid->last_name;
            $parent_kids = \common\models\ParentKids::findAll(['kid_id' => $kid_absence->kid_id, 'status' => ACTIVE]);
            if ($parent_kids) {
                foreach ($parent_kids as $pk) {
                    // Yii::$app->onesignal->sendNotification($pk->parent_id, [
                    //     'content' => Yii::t('app/notification', 'Teacher has confirmed {kid} absence in {day}',['kid'=>$kid_name,'day'=>date('d/m',strtotime($kid_absence->from_date))]),
                    //     'data' => [
                    //         'type' => 'absence',
                    //         'absence_id' => $kid_absence_id,
                    //         'kid_id' => $kid_absence->kid_id,
                    //     ]
                    // ], Yii::$app->onesignal->app_parent);

                    // push to redis push list
                    $content = Yii::t('app/notification', 'Teacher has confirmed {kid} absence in {day}', ['kid' => $kid_name, 'day' => date('d/m', strtotime($kid_absence->from_date))]);
                    $data = [
                        'type' => 'absence',
                        'absence_id' => $kid_absence_id,
                        'kid_id' => $kid_absence->kid_id,
                    ];
                    $push_data = [
                        'uid' => $pk->parent_id,
                        'content' => $content,
                        'data' => json_encode($data),
                    ];
                    //Yii::$app->redis->executeCommand('lpush', [Yii::$app->params['push_notification']['list_parent'], json_encode($push_data)]);
                    Yii::$app->redis->executeCommand('lpush', [getenv('REDIS_LIST_PARENT'), json_encode($push_data)]);

                    $last_push = new ParentPushNotifications();
                    $last_push->parent_id = $pk->parent_id;
                    $last_push->push_type = ParentPushNotifications::PUSH_TYPE_ABSENCE;
                    $last_push->push_content = $content;
                    $last_push->content_id = $kid_absence_id;
                    $last_push->kid_id = $kid_absence->kid_id;
                    $last_push->push_data = json_encode($data);
                    $last_push->pushed_at = date('Y-m-d H:i:s');
                    $last_push->status = ACTIVE;
                    $last_push->save();
                }
            }

            return [
                'name' => 'approve absence submission',
                'code' => 0,
                'status' => 200,
                'message' => 'approve success'
            ];
        } else {
            return $kid_absence->errors;
        }
    }

    /**
     * get approval history
     */
    public function actionHistory()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;

        // paging
        $page = $request->post('page', 1);
        $to_date = $request->post('to_date');
        $from_date = $request->post('from_date');
        $class_id = $request->post('class_id');
        // conditions
        $query = KidAbsences::find()
            ->where(['status' => KidAbsences::APPROVAL, 'class_id' => $class_id]);

        if ($to_date) {
            $query->andWhere(['<=', 'date(created_at)', $to_date]);
        }
        if ($from_date) {
            $query->andWhere(['>=', 'date(created_at)', $from_date]);
        }

        $query->orderBy('id desc')
            ->limit(KidAbsences::PAGE_SIZE)
            ->offset(($page - 1) * KidAbsences::PAGE_SIZE);

        $submissions = $query->all();

        $data = [];
        foreach ($submissions as $submit) {
            $submission_data = $submit->toArray();
            $submission_data['kid'] = $submit->kid->toArray();
            if ($submit->kid->photo) {
                $photo = $submit->kid->photo;
                $submission_data['kid']['photo'] = $photo->toArray();
                $submission_data['kid']['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo);
            } else {
                $submission_data['kid']['photo'] = null;
            }

            $submission_data['class'] = $submit->class;
            $submission_data['absence_reason'] = $submit->absenceReason;
            $data[] = $submission_data;
        }

        return [
            'name' => 'approved absence history',
            'code' => 0,
            'status' => 200,
            'message' => 'get list success',
            'page' => $page,
            'data' => $data
        ];
    }
}
