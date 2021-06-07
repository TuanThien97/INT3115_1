<?php

namespace common\modules\v10\modules\news\controllers;

use Yii;
use common\controllers\ApiController;
// models
use common\models\Teachers;
use common\models\TeacherClasses;
use common\models\Classes;
use common\models\SchoolTeachers;
use common\models\SchoolArticles;
use common\models\Photos;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Default controller for the `news` module
 */
class DefaultController extends \common\controllers\ApiController
{
    /**
     * get news
     * @param int $class_id
     * @param int $page
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $school_grade_teacher_id = $teacher_class->class->school_grade_id;

        $school_id = $teacher_class->class->schoolGrade->school_id;
        $school_teacher = SchoolTeachers::findOne(['school_id' => $school_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$school_teacher) {
            throw new \yii\web\BadRequestHttpException;
        }

        $page = $request->post('page', 1);

        $articles = SchoolArticles::find()
            ->joinWith('schoolArticleSendLogs')
            ->where(['school_id' => $school_id, 'school_articles.status' => ACTIVE])
            ->andWhere([
                'or',
                ['school_article_send_logs.receiver_id' => $teacher_id, 'type' => 'teacher', 'school_article_send_logs.status' => ACTIVE],
                ['school_article_send_logs.receiver_id' => $class_id, 'type' => 'class', 'school_article_send_logs.status' => ACTIVE],
                ['school_article_send_logs.receiver_id' => $school_grade_teacher_id, 'type' => 'grade', 'school_article_send_logs.status' => ACTIVE],
                ['type' => 'school', 'school_article_send_logs.status' => ACTIVE],
                ['type' => null,  'school_article_send_logs.status' => null, 'school_article_send_logs.receiver_id' => null, "school_article_send_logs.id" => null],
            ])

            ->orderBy('created_at DESC')
            ->limit(SchoolArticles::PAGE_SIZE)
            ->offset(($page - 1) * SchoolArticles::PAGE_SIZE)
            ->all();

        $data = [];
        // $data_all = [];

        if ($articles) {
            foreach ($articles as $dt) {
                $article_data = $dt->toArray();
                if ($dt->photo) {
                    $article_data['photo'] = $dt->photo->toArray();
                    $article_data['photo']['url'] = Yii::$app->gcs->getSignedUrl($dt->photo);
                } else {
                    $photo_if_null = Photos::find()->where(['id' => 9])->one();
                    $article_data['photo'] = $photo_if_null->toArray();
                    $article_data['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo_if_null);
                    $article_data['photo_id'] = 9;
                }

                $data[] = $article_data;
            }
        }

        // if ($articles) {
        //     $page_size = SchoolArticles::PAGE_SIZE;
        //     $count = 1;
        //     foreach ($articles as $article) {
        //         $push_data = false;
        //         if ($article->schoolArticleSendLogs) {
        //             $data_logs = $article->schoolArticleSendLogs;
        //             // if(($data_log->receiver_id=$teacher_id ))
        //             foreach ($data_logs as $log) {
        //                 if (
        //                     $log->status == ACTIVE &&
        //                     (
        //                         ($log->receiver_id == $teacher_id && $log->type == 'teacher') ||
        //                         ($log->receiver_id == $class_id && $log->type == 'class') ||
        //                         ($log->receiver_id == $school_grade_teacher_id && $log->type == 'grade') ||
        //                         ($log->type == 'school'))
        //                 ) {
        //                     $push_data = true;
        //                     break;
        //                 }
        //             }
        //         } else {
        //             $push_data = true;
        //         }
        //         if ($push_data == true) {
        //             $data_all[] = $article;
        //         }
        //     }
        //     foreach ($data_all as $dt) {
        //         if ($count > ($page - 1) * $page_size && $count <= $page * $page_size) {
        //             $article_data = $dt->toArray();
        //             if ($dt->photo) {
        //                 $article_data['photo'] = $dt->photo->toArray();
        //                 $article_data['photo']['url'] = Yii::$app->gcs->getSignedUrl($dt->photo);
        //             } else {
        //                 $photo_if_null = Photos::find()->where(['id' => 9])->one();
        //                 $article_data['photo'] = $photo_if_null->toArray();
        //                 $article_data['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo_if_null);
        //                 $article_data['photo_id'] = 9;
        //             }

        //             $data[] = $article_data;
        //         }
        //         $count++;
        //     }
        // }
        return [
            'name' => 'news index',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $data,
        ];
    }

    /**
     * news detail
     * @param int $school_article_id
     */
    public function actionDetail()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $school_article_id = $request->post('school_article_id');
        $article = SchoolArticles::findOne(['id' => $school_article_id, 'status' => ACTIVE]);
        $school_id = $article->school_id;
        $school_teacher = SchoolTeachers::findOne(['school_id' => $school_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$school_teacher) {
            throw new \yii\web\BadRequestHttpException;
        }

        $data = $article->toArray();
        if ($article->photo) {
            $data['photo'] = $article->photo->toArray();
            $data['photo']['url'] = Yii::$app->gcs->getSignedUrl($article->photo);
        } else {
            $photo_if_null = Photos::find()->where(['id' => 9])->one();
            $article_data['photo'] = $photo_if_null->toArray();
            $article_data['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo_if_null);
            $article_data['photo_id'] = 9;
        }

        return [
            'name' => 'news detail',
            'code' => 0,
            'message' => 'success',
            'status' => 200,
            'data' => $data,
        ];
    }
}
