<?php

namespace common\modules\v10\modules\curriculum\controllers;

use Yii;
use common\models\SchoolGradeActivities;
use common\models\ClassTimetableDetails;
use common\models\ClassTimetables;
use common\models\ClassTimetableByweekDetails;
use common\models\ClassTimetableByweeks;
use common\models\Classes;
use yii\rest\Controller;

/**
 * Default controller for the `curriculum` module
 */
class DefaultController extends \common\controllers\ApiController
{
    /**
     * week timetable list
     * 
     * @param int $teacher_id
     * @param int $class_id
     * @param date $to_date
     * @param date $from_date
     * 
     * @throws \yii\web\BadRequestHttpException if teacher class not found 
     * @return mixed
     */
    public function actionTimetable()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'class_id' => $class_id, 'status' => ACTIVE])->one();
        $class = Classes::findOne(['id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        // $dayInput = $request->post('date');
        // if (Yii::$app->request->post('date')) {
        //     $day = strtotime($dayInput);
        //     $dayData = date("D d-m-Y", $day);
        //     $date = date('w', $day);
        // } else {
        //     $dayData = date("D d-m-Y");
        //     $date = date('w');
        // }
        // $data['timetables'] = [];
        // $class_timetables = ClassTimetables::find()
        //     ->where(['class_id' => $class_id, 'dow' => $date, 'status' => ACTIVE])
        //     ->one();
        // $class_timetable_details = ClassTimetableDetails::find()
        //     ->where(['class_timetable_id' => $class_timetables['id'], 'status' => ACTIVE])
        //     ->orderBy(['from_time' => SORT_ASC])
        //     ->all();
        // foreach ($class_timetable_details as $detail) {
        //     $day3['action'] = $detail->schoolGradeActivity->name;
        //     $day3['description'] = $detail->schoolGradeActivity->description;
        //     $day3['from_time'] = $detail->from_time;
        //     $day3['to_time'] = $detail->to_time;
        //     $fromTime = strtotime($detail->from_time);
        //     if (date('H', $fromTime) >= 12) {
        //         $day3['is_am'] = 0;
        //     } else $day3['is_am'] = 1;

        //     $data['timetables'][] = $day3;
        // }
        // $data['date'] = $dayData;
        // $data['class'] = $class->name;

        $dayInput = $request->post('date');
        if (Yii::$app->request->post('date')) {
            $day = strtotime($dayInput);
            $dayData = date("D d-m-Y", $day);
            $date_input = new \DateTime($dayInput);
            $date= $date_input->format('w');
            $week = $date_input->format("W");
            $year = $date_input->format("o");
        } else {
            $dayData = date("D d-m-Y");
            $date_input = new \DateTime();
            $date= $date_input->format('w');
            $week = $date_input->format("W");
            $year = $date_input->format("o");
        }
        $data['timetables'] = [];
        $class_timetables = ClassTimetableByweeks::find()
            ->where(['class_id' => $class_id, 'week' => $week, 'year' => $year, 'status' => ACTIVE])
            ->one();
        $class_timetable_details = ClassTimetableByweekDetails::find()
            ->where(['class_timetable_byweek_id' => $class_timetables['id'], 'dow' => $date, 'status' => ACTIVE])
            ->orderBy(['sequence' => SORT_ASC, 'from_time' => SORT_ASC])
            ->all();
        foreach ($class_timetable_details as $detail) {
            $day3['action'] = $detail->schoolGradeActivity->name;
            $day3['description'] = $detail->schoolGradeActivity->description;
            $day3['from_time'] = $detail->from_time;
            $day3['to_time'] = $detail->to_time;
            $fromTime = strtotime($detail->from_time);
            if (date('H', $fromTime) >= 12) {
                $day3['is_am'] = 0;
            } else $day3['is_am'] = 1;

            $data['timetables'][] = $day3;
        }
        $data['date'] = $dayData;
        $data['class'] = $class->name;
        if ($class_timetables) {
            $data['topic'] = $class_timetables->topic;
        }

        return [
            'name' => 'Timetables',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $data,
        ];
    }

    /**
     * week timetable list
     * 
     * @param int $teacher_id
     * @param int $class_id
     * @param date $to_date
     * @param date $from_date
     * 
     * @throws \yii\web\BadRequestHttpException if teacher class not found 
     * @return mixed
     */
    public function actionWeekTimetable()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'class_id' => $class_id, 'status' => ACTIVE])->one();
        $class = Classes::findOne(['id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $to_date = $request->post('to_date', date('Y-m-d'));
        $from_date = $request->post('from_date');
        $data = [];
        $class_timetables = [];

        $start_date = date('Y-m-d', strtotime($from_date));
        $end_date = date('Y-m-d', strtotime($to_date));
        if ($end_date > $to_date) {
            $end_date = $to_date;
        }

        $start = $start_date;
        while ($start <= $end_date) {
            $dayOfWeek = date('w', strtotime($start));
            $class_timetables[$start] = ClassTimetables::find()
                ->where(['class_id' => $class_id, 'dow' => $dayOfWeek, 'status' => ACTIVE])->asArray()
                ->one();
            $start = strtotime($start) + (3600 * 24);
            $start = date('Y-m-d', $start);
        }
        if (count($class_timetables) != 0) {
            foreach ($class_timetables as $key => $value) {
                $day4 = [];
                $day4 = $value;
                $day4['day'] = $key;
                $class_timetable_details = ClassTimetableDetails::find()
                    ->where(['class_timetable_id' => $value['id'], 'status' => ACTIVE])
                    ->orderBy(['from_time' => SORT_ASC])
                    ->all();

                foreach ($class_timetable_details as $detail) {
                    $day3['action'] = $detail->schoolGradeActivity->name;
                    $day3['description'] = $detail->schoolGradeActivity->description;
                    $day3['from_time'] = $detail->from_time;
                    $day3['to_time'] = $detail->to_time;
                    $fromTime = strtotime($detail->from_time);
                    if (date('H', $fromTime) >= 12) {
                        $day3['is_am'] = 0;
                    } else $day3['is_am'] = 1;
                    $day4['timetables'][] = $day3;
                }
                $data[] = $day4;
            }
        }
        return [
            'name' => 'Week Timetables',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $data,

        ];
    }
}
