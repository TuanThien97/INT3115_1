<?php

namespace common\modules\v10\modules\checkin\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\UploadedFile;
use yii\db\Query;
use common\models\Photos;
// models
use common\models\KidCheckins;
use common\models\KidCheckinPhotos;
use common\models\TeacherClasses;
use common\models\KidClasses;
use common\models\PrincipalNotifications;
use common\models\ParentPushNotifications;
use common\models\TeacherPushNotifications;

/**
 * Default controller for the `checkin` module
 */
class DefaultController extends \common\controllers\ApiController
{
    /**
     * list kid from class
     * @param int $class_id
     * @param date $day (optional)
     */
    public function actionListKids()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $day = $request->post('day');
        if (!$day) {
            $date = new \DateTime();
            $day = $date->format('Y-m-d');
        }
        $type = $request->post('type');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }



        $class_kids = \common\models\KidClasses::find()
            ->where(['class_id' => $class_id, 'kid_classes.status' => ACTIVE])
            ->joinWith('kid k')
            ->orderBy(['k.last_name' => SORT_ASC, 'k.first_name' => SORT_ASC])
            ->all();
        // $data = [];
        // foreach ($class_kids as $class_kid){
        //     $data_kid = $class_kid->toArray();
        //     $data_kid['kid'] = $class_kid->kid;
        //     $data[] = $data_kid;
        // }

        // get checkins in day
        $kid_checkins = KidCheckins::find()
            ->where(['date(day)' => $day, 'class_id' => $class_id])
            ->andWhere(['!=', 'status', INACTIVE])
            ->orderBy(['status' => SORT_ASC, 'created_at' => SORT_DESC])
            ->all();
        $kid_checkin_ids = [];
        foreach ($kid_checkins as $kc) {
            $kid_checkin_ids[] = $kc->kid_id;
        }
        $kid_checkouts = \common\models\KidCheckouts::find()
            ->where(['date(day)' => $day, 'class_id' => $class_id])
            ->andWhere(['status' => ACTIVE])
            ->orderBy(['status' => SORT_ASC, 'created_at' => SORT_DESC])
            ->all();
        $kid_checkout_ids = [];
        foreach ($kid_checkouts as $ko) {
            $kid_checkout_ids[] = $ko->kid_id;
        }

        $data = [];
        if ($type == KidCheckins::TYPE_CREATE) {
            foreach ($class_kids as $kc) {
                if (!in_array($kc->kid_id, $kid_checkout_ids)) {
                    if (!in_array($kc->kid_id, $kid_checkin_ids)) {
                        //$data_kid = $ck->toArray();
                        $data_kid = $kc->kid->toArray();
                        $kid_absence = \common\models\KidAbsences::find()
                            ->where(['kid_id' => $kc->kid_id, 'class_id' => $kc->class_id])
                            ->andWhere(['!=', 'status', INACTIVE])
                            ->andWhere(['<=', 'from_date', $day])
                            ->andWhere(['>=', 'to_date', $day])
                            ->one();

                        if ($kid_absence != null) {
                            $data_kid['is_absence'] = 1;
                        } else {
                            $data_kid['is_absence'] = 0;
                        }
                        if ($kc->kid->photo) {
                            $data_kid['photo'] = $kc->kid->photo->toArray();
                            $data_kid['photo']['url'] = Yii::$app->gcs->getSignedUrl($kc->kid->photo);
                        } else {
                            $data_kid['photo'] = null;
                        }

                        $data_kid['is_checkedin'] = 0;
                        $data[] = $data_kid;
                    }
                }
            }
        }
        foreach ($kid_checkins as $kc) {
            if (!in_array($kc->kid_id, $kid_checkout_ids)) {
                $data_kid = $kc->kid->toArray();
                if ($kc->kid->photo) {
                    $data_kid['photo'] = $kc->kid->photo->toArray();
                    $data_kid['photo']['url'] = Yii::$app->gcs->getSignedUrl($kc->kid->photo);
                } else {
                    $data_kid['photo'] = null;
                }

                //$data_kid['kid'] = $kc->kid;
                $data_kid['is_checkedin'] = 1;
                $data_kid['checkin_status'] = $kc->status;
                $data[] = $data_kid;
            }
        }

        // SORT BY NAME
        // $keys = array_column($data,'first_name','last_name');
        // array_multisort($keys, SORT_ASC, $data);
        // END SORT

        return [
            'name' => 'get list kids in class',
            'code' => 0,
            'message' => 'list kids success',
            'status' => 200,
            'data' => $data
        ];
    }

    /**
     * create checkin
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $day = $request->post('day');
        if ($day) {
            $day = date('Y-m-d', strtotime($day));
        } else {
            throw new \yii\web\BadRequestHttpException;
        }

        $checkins = $request->post("checkins");
        $data = [];
        foreach ($checkins as $checkin) {
            $kid_id = $checkin['kid_id'];
            $status = $checkin['status'];
            $photos = $checkin['photos'];
            // verify kids belongs to class
            $kid_class = \common\models\KidClasses::findOne(['kid_id' => $kid_id, 'class_id' => $class_id, 'status' => ACTIVE]);
            if ($kid_class) {
                $kid_checkin = \common\models\KidCheckins::find()
                    ->where([
                        'kid_id' => $kid_id,
                        'class_id' => $class_id,
                        //'teacher_id' => $teacher_id,
                    ])
                    ->andWhere(['=', 'date(day)', $day])->one();

                if ($kid_checkin) {
                    // update
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $kid_checkin->day = $day;
                        $kid_checkin->status = $checkin['status'];
                        if ($kid_checkin->save()) {
                            KidCheckinPhotos::deleteAll(['kid_checkin_id' => $kid_checkin->id]);
                            if (!empty($photos)) {
                                foreach ($photos as $photo_id) {
                                    $photo = new KidCheckinPhotos();
                                    $photo->kid_checkin_id = $kid_checkin->id;
                                    $photo->photo_id = $photo_id;
                                    $photo->status = ACTIVE;
                                    if ($photo->save()) {
                                    } else {
                                        return $photo->errors;
                                    }
                                }
                            }
                        } else {
                            return $kid_checkin->errors;
                        }
                        $data[] = $kid_checkin;
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                } else {
                    // create
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $kid_checkin = new \common\models\KidCheckins();
                        $kid_checkin->kid_id = $kid_id;
                        $kid_checkin->class_id = $class_id;
                        $kid_checkin->teacher_id = $teacher_id;
                        $kid_checkin->day = $day;
                        $kid_checkin->note = isset($checkin['note']) ? $checkin['note'] : null;
                        $kid_checkin->status = $status;
                        if ($kid_checkin->save()) {
                            if (!empty($photos)) {
                                foreach ($photos as $photo_id) {
                                    $photo = new KidCheckinPhotos();
                                    $photo->kid_checkin_id = $kid_checkin->id;
                                    $photo->photo_id = $photo_id;
                                    $photo->status = ACTIVE;
                                    if ($photo->save()) {
                                    } else {
                                        return $photo->errors;
                                    }
                                }
                            }
                        } else {
                            return $kid_checkin->errors;
                        }
                        $data[] = $kid_checkin;
                        // send notification
                        if ($checkin['status'] == KidCheckins::PRESENCE) {
                            $kid_name = $kid_checkin->kid->first_name . ' ' . $kid_checkin->kid->last_name;
                            $parent_kids = \common\models\ParentKids::findAll(['kid_id' => $kid_id, 'status' => ACTIVE]);
                            if ($parent_kids) {
                                foreach ($parent_kids as $pk) {
                                    // push to redis push list
                                    $content = Yii::t('app/notification', '{kid} has been checked in in {day}', ['kid' => $kid_name, 'day' => date('d/m', strtotime($day))]);
                                    $data = [
                                        'type' => 'checkin',
                                        'day' => $day,
                                        'kid_id' => $kid_id,
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
                                    $last_push->push_content = $content;
                                    $last_push->kid_id = $kid_id;
                                    $last_push->content_id = $kid_checkin->id;
                                    $last_push->push_data = json_encode($data);
                                    $last_push->push_type = ParentPushNotifications::PUSH_TYPE_CHECKIN;
                                    $last_push->pushed_at = date('Y-m-d H:i:s');
                                    $last_push->status = ACTIVE;
                                    $last_push->save();
                                }
                            }
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            } else {
                throw new \yii\web\BadRequestHttpException;
            }
        }
        return [
            'name' => 'create checkins',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $data,
        ];
    }

    /**
     * list checkins 
     */
    public function actionHistory()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $now_date = date('Y-m-d');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $page = $request->post('page', 1);
        $to_date = $request->post('to_date');
        $from_date = $request->post('from_date');

        // conditions
        $total_kids = \common\models\KidClasses::find()
            ->where(['class_id' => $class_id, 'status' => ACTIVE])
            ->count();

        $total_query = (new Query())->select(['DATE(day) as date', 'count(distinct(kid_id)) as total'])->from('kid_checkins')
            ->leftJoin('kids', 'kids.id=kid_checkins.kid_id')
            ->where(['!=', 'kid_checkins.status', INACTIVE])
            ->andWhere([
                //'teacher_id' => $teacher_id, 
                'kid_checkins.class_id' => $class_id,
                'kids.status' => ACTIVE,
            ])->distinct();

        if ($from_date) {
            $from_date = date('Y-m-d', strtotime($from_date));
            $total_query->andWhere(['>=', 'DATE(day)', $from_date]);
        }

        if ($to_date) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_query->andWhere(['<=', 'DATE(day)', $to_date]);
        }

        $total_query->groupBy('DATE(day)')->orderBy('date desc')
            ->limit(KidCheckins::PAGE_SIZE)
            ->offset(($page - 1) * KidCheckins::PAGE_SIZE);

        $total = $total_query->all();

        $absence_query = (new Query())->select(['DATE(day) as date', 'count(distinct(kid_id)) as total'])->from('kid_checkins')
            ->leftJoin('kids', 'kids.id=kid_checkins.kid_id')
            ->where(['in', 'kid_checkins.status', [KidCheckins::ABSENCE_WITH_REASON, KidCheckins::ABSENCE_WITHOUT_REASON]])
            ->andWhere([
                //'teacher_id' => $teacher_id, 
                'class_id' => $class_id,
                'kids.status' => ACTIVE,
            ]);

        if ($from_date) {
            $absence_query->andWhere(['>=', 'DATE(day)', $from_date]);
        }

        if ($to_date) {
            $absence_query->andWhere(['<=', 'DATE(day)', $to_date]);
        }

        $absence_query->groupBy('DATE(day)')->orderBy('date desc')
            ->limit(KidCheckins::PAGE_SIZE)
            ->offset(($page - 1) * KidCheckins::PAGE_SIZE);
        $absence = $absence_query->all();

        $data = [];

        foreach ($total as $checkin) {
            $day['date'] = $checkin['date'];

            $day['total'] = $total_kids;
            $day['absence'] = 0;
            foreach ($absence as $acheckin) {
                if ($acheckin['date'] == $day['date']) {
                    $day['absence'] = $acheckin['total'];
                }
            }
            $day['checkin'] = $checkin['total'] - $day['absence'];
            $data[] = $day;
        }
        if (empty($data)) {
            $data[] = [
                'total' => $total_kids,
            ];
        }

        return [
            'name' => 'list checkins by day',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /**
     * checkins by date detail
     */
    public function actionDetailByDay()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $day = $request->post('day');
        $kid_checkins = KidCheckins::find()->joinWith('kid')
            ->where([
                'kid_checkins.class_id' => $class_id,
                'kids.status' => ACTIVE
                // , 'teacher_id' => $teacher_id
            ])
            ->orderBy(['kid_checkins.status' => SORT_DESC, 'kids.last_name' => SORT_ASC, 'kids.first_name' => SORT_ASC])
            ->andWhere(['=', 'date(day)', $day])
            ->andWhere(['!=', 'kid_checkins.status', INACTIVE])
            ->all();

        $data = [];
        $data['class_id'] = $class_id;
        $data['class'] = $teacher_class->class;
        $data['teacher_id'] = $teacher_id;
        $data['day'] = $day;
        $data['checkins'] = [];
        $list_checkin_ids = [];
        foreach ($kid_checkins as $kci) {
            if (in_array($kci->kid_id, $list_checkin_ids)) {
                continue;
            }
            $data_kci = $kci->toArray();
            $data_kci['kid'] = $kci->kid->toArray();
            if ($kci->kid->photo) {
                $data_kci['kid']['photo'] = $kci->kid->photo->toArray();
                $data_kci['kid']['photo']['url'] = Yii::$app->gcs->getSignedUrl($kci->kid->photo);
            } else {
                $data_kci['kid']['photo'] = null;
            }

            $data_kci['photos'] = [];
            foreach ($kci->kidCheckinPhotos as $kci_photo) {
                $data_photo = $kci_photo->photo->toArray();
                $data_photo['url'] = Yii::$app->gcs->getSignedUrl($kci_photo->photo);
                $data_kci['photos'][] = $data_photo;
            }

            $data['checkins'][] = $data_kci;
            $list_checkin_ids[] = $kci->kid_id;
        }
        return [
            'name' => 'checkins detail by day',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }
    /**
     * upload photo
     * @param file $photo
     * @param int $class_id
     * @param int $kid_id
     */
    public function actionCountCheckinDay()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $day = $request->post('day', date('Y-m-d'));
        $list_presence_ids = [];
        $list_absence_ids = [];
        $count_absence = 0;
        $count_checkin = 0;
        $data = [];

        $checkin_presences = KidCheckins::find()
            ->joinWith('kid')
            ->where([
                'kid_checkins.class_id' => $class_id,
                'DATE(day)' => $day, 'kids.status' => ACTIVE,
                'kid_checkins.status' => KidCheckins::PRESENCE
            ])
            ->all();
        $checkin_absences = KidCheckins::find()
            ->joinWith('kid')
            ->where([
                'kid_checkins.class_id' => $class_id,
                'DATE(day)' => $day, 'kids.status' => ACTIVE
            ])
            ->andWhere(['in', 'kid_checkins.status', [KidCheckins::ABSENCE_WITH_REASON, KidCheckins::ABSENCE_WITHOUT_REASON]])
            ->all();
        foreach ($checkin_presences as $ck) {
            if (in_array($ck->kid_id, $list_presence_ids)) {
                continue;
            }
            $count_checkin++;
            $list_presence_ids[] = $ck->kid_id;
        }
        foreach ($checkin_absences as $ck) {
            if (in_array($ck->kid_id, $list_absence_ids)) {
                continue;
            }
            $count_absence++;
            $list_absence_ids[] = $ck->kid_id;
        }
        $count_no_checkin = KidClasses::find()
            ->where(['class_id' => $class_id, 'status' => ACTIVE])
            ->andWhere(['not in', 'kid_id', $list_presence_ids])
            ->andWhere(['not in', 'kid_id', $list_absence_ids])
            ->count();
        $data['checkin_absence'] = $count_absence;
        $data['checkin_presence'] = $count_checkin;
        $data['no_checkin'] = $count_no_checkin;
        return [
            'name' => 'count checkin day',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }


    /**
     * upload photo
     * @param file $photo
     * @param int $class_id
     * @param int $kid_id
     */
    public function actionUploadPhoto()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $file = UploadedFile::getInstanceByName('photo');
        Yii::debug('file has uploaded: ' . json_encode($file));
        //print_r($file);die;        
        if (!$file) {
            throw new \yii\web\BadRequestHttpException;
        }
        if ($validation = Yii::$app->gcs->validate($file)) {
            return $validation;
        }

        // validation
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id  = $request->post('class_id');
        $kid_id = $request->post('kid_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        if ($kid_id) {
            $kid_class = \common\models\KidClasses::findOne(['class_id' => $class_id, 'kid_id' => $kid_id, 'status' => ACTIVE]);
            if (!$kid_class) {
                throw new \yii\web\BadRequestHttpException;
            }
        }

        $class_id = $teacher_class->class->id;
        $school_id = $teacher_class->class->schoolGrade->school->id;
        $bucket = getenv('GCP_BUCKET_PRIVATE');
        $module = \Yii::$app->controller->module;
        $filepath = Yii::$app->gcs->getFilePath($school_id, $class_id, $module->id);
        //$filename = Yii::$app->gcs->getFilename($file);
        $data = [
            'teacher_id' => $teacher_id,
            'class_id' => $class_id,
            'kid_id' => $kid_id,
        ];
        if ($upload = Yii::$app->gcs->upload($bucket, $filepath, $file, $data)) {
            return $upload;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
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
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
        $class_id  = $request->post('class_id');
        $kid_id = $request->post('kid_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        if ($kid_id) {
            $kid_class = \common\models\KidClasses::findOne(['class_id' => $class_id, 'kid_id' => $kid_id, 'status' => ACTIVE]);
            if (!$kid_class) {
                throw new \yii\web\BadRequestHttpException;
            }
        }

        $class_id = $teacher_class->class->id;
        $school_id = $teacher_class->class->schoolGrade->school->id;
        $bucket = getenv('GCP_BUCKET_PRIVATE');
        $module = \Yii::$app->controller->module;
        $filepath = Yii::$app->gcs->getFilePath($school_id, $class_id, $module->id);
        $data = [
            'teacher_id' => $teacher_id,
            'class_id' => $class_id,
            'kid_id' => $kid_id,
            'is_trivial' => Photos::IS_TRIVIAL,
        ];

        if ($upload = Yii::$app->gcs->uploadBase64($bucket, $filepath, $filename, $base64, $data)) {
            return $upload;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }
    public function actionQrcodeCheckin()
    {
        // Init date
        $day = date('Y-m-d H:i:s');
        $year = date('Y');
        $month = date('m');
        $day1 = date('d');

        // Handle Request
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $code = $request->post('code');
        $class_id = $request->post('class_id');

        //$teacher_class = \common\models\TeacherClasses::findOne(['teacher_id' => $teacher_id, 'status' => ACTIVE]);
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);

        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found teacher class');
        }

        if (!$code) {
            throw new \yii\web\BadRequestHttpException('not found code');
        } else {
            $prefix = substr($code, 0, 1);
            if ($prefix == "K") {
                // Find Kids by Code
                $kid_info = \common\models\Kids::find()->where(['code' => $code, 'status' => ACTIVE])->one();
                if (!$kid_info) {
                    return [
                        'name' => 'create kid checkin qrcode',
                        'code' => -2,
                        'status' => 400,
                        'message' => 'wrong qrcode',
                    ];
                }
                $data = [];
                $kid_class = \common\models\KidClasses::findOne(['kid_id' => $kid_info->id, 'status' => ACTIVE]);

                $school_ids = \common\models\Schools::find()
                    ->leftJoin('school_grades', 'school_grades.school_id=schools.id')
                    ->leftJoin('classes', 'classes.school_grade_id=school_grades.id')
                    ->where(['classes.id' => $class_id, 'classes.status' => ACTIVE, 'school_grades.status' => ACTIVE, 'schools.status' => ACTIVE])
                    ->one();
                if (!$school_ids) {
                    throw new \yii\web\BadRequestHttpException('not found school');
                }
                $school_id = $school_ids->id;
                $kid_school = \common\models\SchoolKids::findOne(['kid_id' => $kid_info->id, 'school_id' => $school_id, 'status' => ACTIVE]);
                if (!$kid_school) {
                    return [
                        'name' => 'create kid checkin qrcode',
                        'code' => -2,
                        'status' => 400,
                        'message' => 'wrong qrcode',
                    ];
                }

                if ($kid_class) {
                    $kid_checkin = \common\models\KidCheckins::find()
                        ->where([
                            'kid_id' => $kid_info->id,
                            "EXTRACT(year from day)" => $year,
                            "EXTRACT(month from day)" => $month,
                            "EXTRACT(day from day)" => $day1
                        ])
                        ->one();
                    if ($kid_checkin) {
                        return [
                            'name' => 'create kid checkin qrcode',
                            'code' => -1,
                            'status' => 400,
                            'message' => 'kid checkin exists',
                        ];
                    } else {
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $kid_checkin = new \common\models\KidCheckins();
                            $kid_checkin->kid_id = $kid_info->id;
                            $kid_checkin->class_id = $kid_class->class_id;
                            $kid_checkin->teacher_id = $teacher_id;
                            $kid_checkin->day = $day;
                            $kid_checkin->is_qrcode = 1;
                            $kid_checkin->status = KidCheckins::PRESENCE;
                            if ($kid_checkin->save()) {
                            } else {
                                return $kid_checkin->errors;
                            }
                            $data = $kid_checkin;
                            // send notification
                            if ($kid_checkin['status'] == KidCheckins::PRESENCE) {
                                $kid_name = $kid_checkin->kid->first_name . ' ' . $kid_checkin->kid->last_name;
                                $parent_kids = \common\models\ParentKids::findAll(['kid_id' => $kid_info->id, 'status' => ACTIVE]);
                                if ($parent_kids) {
                                    foreach ($parent_kids as $pk) {
                                        $content = Yii::t('app/notification', '{kid} has been checked in in {day}', ['kid' => $kid_name, 'day' => date('d/m', strtotime($day))]);
                                        $data = [
                                            'type' => 'checkin',
                                            'day' => $day,
                                            'kid_id' => $kid_info->id,
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
                                        $last_push->push_content = $content;
                                        $last_push->push_data = json_encode($data);
                                        $last_push->content_id = $kid_checkin->id;
                                        $last_push->kid_id = $kid_info->id;
                                        $last_push->push_type = ParentPushNotifications::PUSH_TYPE_CHECKIN;
                                        $last_push->pushed_at = date('Y-m-d H:i:s');
                                        $last_push->status = ACTIVE;
                                        $last_push->save();
                                    }
                                }
                            }


                            $transaction->commit();
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        } catch (\Throwable $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                    }
                } else {
                    throw new \yii\web\BadRequestHttpException('not found kid class');
                }
                return [
                    'name' => 'create qr checkins',
                    'code' => 0,
                    'message' => 'success',
                    'status' => 200,
                    'data' => $data,
                ];
            } else {
                return [
                    'name' => 'create kid checkin qrcode',
                    'code' => -2,
                    'status' => 400,
                    'message' => 'wrong qrcode',
                ];
            }
        }
    }
}
