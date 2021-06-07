<?php

namespace common\modules\v10\modules\absence\controllers;

use Yii;
use common\models\KidAbsences;
use common\models\TeacherClasses;
use yii\rest\Controller;
use common\models\ParentPushNotifications;
use common\models\TeacherPushNotifications;
use common\models\PrincipalPushNotifications;
use common\models\TeacherAbsences;
use common\models\TeacherCheckins;

/**
 * Default controller for the `absence` module
 */
class TeacherAbsenceController extends \common\controllers\ApiController
{

    /**
     * get count absence in month
     */
    public function actionGetCountTeacherAbsence()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $date_input = new \DateTime();
        $month = $request->post('month', $date_input->format('m'));

        $data = [];
        $data['teacher_absence_month'] = 0;
        $data['absence_wait'] = 0;
        $data['absence_reject'] = 0;

        $teacher_checkin = TeacherCheckins::find()
            ->where(['teacher_id' => $teacher_id, "EXTRACT(month from checkin_time)" => $month])
            ->andWhere(['!=', 'status', INACTIVE])
            ->andWhere(['!=', 'status', ACTIVE])->count();
        $teacher_absence_wait = TeacherAbsences::find()->where(['teacher_id' => $teacher_id, "EXTRACT(month from created_at)" => $month, 'status' => TeacherAbsences::WAIT])->count();
        $teacher_absence_reject = TeacherAbsences::find()->where(['teacher_id' => $teacher_id, "EXTRACT(month from created_at)" => $month, 'status' => TeacherAbsences::REJECT])->count();
        $data['teacher_absence_month'] = $teacher_checkin;
        $data['absence_wait'] = $teacher_absence_wait;
        $data['absence_reject'] = $teacher_absence_reject;

        return [
            'name' => 'get data absence month',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data,
        ];
    }

    /**
     * create teacher absen ce
     */

    public function actionCreateTeacherAbsence()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $content = $request->post('content');
        $from_date = $request->post('from_date');
        $to_date = $request->post('to_date');
        $school_id = $teacher_class->class->schoolGrade->school_id;
        // check existence
        $exist = TeacherAbsences::find()->where([
            'teacher_id' => $teacher_id,
            'class_id' => $class_id
        ])
            ->andWhere(['=', 'date(from_date)', $from_date])
            ->andWhere(['=', 'date(to_date)', $to_date])
            ->one();
        if ($exist) {
            return [
                'name' => 'create absence application',
                'code' => -1,
                'status' => 400,
                'message' => 'absence application exists',
            ];
        } else {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // create new application
                $application = new TeacherAbsences();
                $application->class_id = $class_id;
                $application->teacher_id = $teacher_id;
                $application->content = $content;
                $application->from_date = $from_date;
                $application->to_date = $to_date;
                $application->status = TeacherAbsences::WAIT;
                if ($application->save()) {
                    // send notification
                    $teacher_name = $application->teacher->first_name . ' ' . $application->teacher->last_name;
                    $principal_schools = \common\models\SchoolTeachers::find()
                        ->joinWith('teacher')
                        ->where([
                            'school_id' => $school_id,
                            'school_teachers.status' => ACTIVE,
                            'teachers.status' => ACTIVE,
                            'teachers.role' => PRINCIPAL_ROLE
                        ])
                        ->all();
                    if ($principal_schools) {
                        foreach ($principal_schools as $ps) {
                            // push to redis push list
                            $content = Yii::t('app/notification', 'New absence application for {day} from {teacher}', ['teacher' => $teacher_name, 'day' => date('d/m', strtotime($from_date))]);
                            $data = [
                                'type' => 'teacherabsence',
                                'teacher_absence_id' => $application->id,
                                'class_id' => $class_id,
                            ];
                            $push_data = [
                                'uid' => $ps->teacher_id,
                                'content' => $content,
                                'data' => json_encode($data),
                            ];
                            Yii::$app->redis->executeCommand('lpush', [getenv('REDIS_LIST_PRINCIPAL'), json_encode($push_data)]);

                            $last_push = new PrincipalPushNotifications();
                            $last_push->principal_id = $ps->teacher_id;
                            $last_push->push_type = PrincipalPushNotifications::PUSH_TYPE_TEACHER_ABSENCE;
                            $last_push->school_id = $school_id;
                            $last_push->content_id = $application->id;
                            $last_push->push_content = $content;
                            $last_push->push_data = json_encode($data);
                            $last_push->pushed_at = date('Y-m-d H:i:s');
                            $last_push->status = ACTIVE;
                            $last_push->save();
                        }
                    }
                } else {
                    return  [
                        'name' => 'create absence application',
                        'code' => -2,
                        'status' => 400,
                        'message' => 'error',
                        'data' => $application->errors,
                    ];
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
        return [
            'name' => 'create absence application',
            'code' => 0,
            'status' => 200,
            'message' => 'create success',
            'data' => $application,
        ];
    }


    public function actionEdit()
    {

        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_absence_id = $request->post('teacher_absence_id');

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('teacher class not found');
        }
        $content = $request->post('content');
        $from_date = $request->post('from_date');
        $to_date = $request->post('to_date');

        $application = TeacherAbsences::find()->where(['id' => $teacher_absence_id])->one();
        if ($application->status == TeacherAbsences::APROVAL || $application->status == TeacherAbsences::REJECT) {
            return
                [
                    'name' => 'create absence application',
                    'code' => -3,
                    'status' => 400,
                    'message' => 'can not edit absence',
                ];
        }

        if (!$application) {
            throw new \yii\web\BadRequestHttpException('teacher absence not found');
        }

        $application->class_id = $class_id;
        $application->teacher_id = $teacher_id;
        $application->content = $content;
        $application->from_date = $from_date;
        $application->to_date = $to_date;
        if ($application->save()) {
            return [
                'name' => 'edit absence application',
                'code' => 0,
                'status' => 200,
                'message' => 'success',
                'data' => $application,
            ];
        } else {
            return [
                'name' => 'edit absence application',
                'code' => -2,
                'status' => 400,
                'message' => 'error',
                'data' => $application->errors,
            ];
        }
    }

    public function actionHistory()
    {

        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');

        $date_input = new \DateTime();
        $month = $request->post('month', $date_input->format('m'));

        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $absences = TeacherAbsences::find()
            ->where(['teacher_id' => $teacher_id, "EXTRACT(month from created_at)" => $month])
            ->andWhere(['!=', 'status', INACTIVE])
            ->orderBy('id desc')
            ->all();

        $data = [];
        $count = [];

        $count['teacher_absence_month'] = 0;
        $count['absence_wait'] = 0;
        $count['absence_reject'] = 0;

        $teacher_absence_wait = TeacherAbsences::find()->where(['teacher_id' => $teacher_id, "EXTRACT(month from created_at)" => $month, 'status' => TeacherAbsences::WAIT])->count();
        $teacher_absence_reject = TeacherAbsences::find()->where(['teacher_id' => $teacher_id, "EXTRACT(month from created_at)" => $month, 'status' => TeacherAbsences::REJECT])->count();

        $count['absence_wait'] = $teacher_absence_wait;
        $count['absence_reject'] = $teacher_absence_reject;



        foreach ($absences as $abs) {
            $new_abs = $abs->toArray();
            $data[] = $new_abs;
        }
        $today = date('Y-m-d');
        $listDisable = [];
        $list_absence_approval = [];
        $queryGetDateDisable = TeacherAbsences::find()
            ->select(['from_date', 'to_date', 'status', 'created_at'])
            ->where(['teacher_id' => $teacher_id])
            ->andWhere([">=", "EXTRACT(month from created_at)", $month])
            ->all();
        foreach ($queryGetDateDisable as $key => $value) {
            $period = $this->GetDays($value->from_date, $value->to_date);
            foreach ($period as $key => $valueDate) {
                $listDisable[] = $valueDate;
            }
            if ($value->status == TeacherAbsences::APROVAL && date('m', strtotime($value->created_at)) == $month) {
                foreach ($period as $key => $valueDate) {
                    $list_absence_approval[] = $valueDate;
                }
            }
        }
        if ($listDisable) {
            $listDisable = array_unique($listDisable);
        }
        if ($list_absence_approval) {
            $list_absence_approval = array_unique($list_absence_approval);
            $count['teacher_absence_month'] = count($list_absence_approval);
        }

        return [
            'name' => 'absence history',
            'code' => 0,
            'status' => 200,
            'message' => 'get history success',
            'data' => $data,
            'count' => $count,
            'listDisable' => $listDisable
        ];
    }


    /**
     * absence application detail
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        $parent_id = Yii::$app->user->identity->id;
        $teacher_absence_id = $request->post('teacher_absence_id');
        $kid_absence = TeacherAbsences::find()
            ->where(['id' => $teacher_absence_id])
            ->andWhere(['!=', 'status', INACTIVE])->one();
        if (!$kid_absence) {
            throw new \yii\web\BadRequestHttpException('teacher absence not found');
        }

        $data = $kid_absence->toArray();
        return [
            'name' => 'absence application detail',
            'code' => 0,
            'status' => 200,
            'message' => 'get detail success',
            'data' => $data,
        ];
    }

    public function GetDays($sStartDate, $sEndDate)
    {
        // Firstly, format the provided dates.  
        // This function works best with YYYY-MM-DD  
        // but other date formats will work thanks  
        // to strtotime().  
        $sStartDate = date("Y-m-d", strtotime($sStartDate));
        $sEndDate = date("Y-m-d", strtotime($sEndDate));

        // Start the variable off with the start date  
        $aDays[] = $sStartDate;

        // Set a 'temp' variable, sCurrentDate, with  
        // the start date - before beginning the loop  
        $sCurrentDate = $sStartDate;

        // While the current date is less than the end date  
        while ($sCurrentDate < $sEndDate) {
            // Add a day to the current date  
            $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

            // Add this new day to the aDays array  
            $aDays[] = $sCurrentDate;
        }

        // Once the loop has finished, return the  
        // array of days.  
        return $aDays;
    }
}
