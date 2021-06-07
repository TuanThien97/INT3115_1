<?php

namespace common\modules\v10\modules\photos\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\UploadedFile;
// models
use common\models\Teachers;
use common\models\TeacherClasses;
use common\models\Albums;
use common\models\AlbumPhotos;
use common\models\Photos;
use common\models\PhotoLikes;
use common\models\PhotoComments;
use common\models\AlbumLikes;
use common\models\AlbumComments;
use common\models\ParentPushNotifications;
use console\models\ParentPushNotifications as ConsoleParentPushNotifications;

/**
 * Default controller for the `photos` module
 */
class DefaultController extends \common\controllers\ApiController
{
    /**
     * upload report's photo
     * @param file $photo
     * @param int $class_id
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
        //$kid_id = $request->post('kid_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        // if ($kid_id) {
        //     $kid_class = \common\models\KidClasses::findOne(['class_id' => $class_id, 'kid_id' => $kid_id, 'status' => ACTIVE]);
        //     if (!$kid_class) {
        //         throw new \yii\web\BadRequestHttpException;
        //     }
        // }

        $class_id = $teacher_class->class->id;
        $school_id = $teacher_class->class->schoolGrade->school->id;
        $bucket = getenv('GCP_BUCKET_PRIVATE');
        $module = \Yii::$app->controller->module;
        $filepath = Yii::$app->gcs->getFilePath($school_id, $class_id, $module->id);
        //$filename = Yii::$app->gcs->getFilename($file);
        $data = [
            'teacher_id' => $teacher_id,
            'class_id' => $class_id,
            'title' => $request->post('title'),
            'description' => $request->post('description'),
        ];
        if ($upload = Yii::$app->gcs->upload($bucket, $filepath, $file, $data)) {
            // send notification
            $kid_class = \common\models\KidClasses::findAll(['class_id' => $class_id, 'status' => ACTIVE]);
            foreach ($kid_class as $kc) {
                $kid_name = $kc->kid->first_name . ' ' . $kc->kid->last_name;
                $parent_kids = \common\models\ParentKids::findAll(['kid_id' => $kc->kid_id, 'status' => ACTIVE]);
                if ($parent_kids) {
                    foreach ($parent_kids as $pk) {
                        Yii::$app->onesignal->sendNotification($pk->parent_id, [
                            'content' => Yii::t('app/notification', '{kid} has new photo', ['kid' => $kid_name]),
                            'data' => [
                                'type' => 'photo',
                                'url' => $upload['url'],
                                'kid_id' => $kc->kid_id,
                                'photo_id' => $upload['photo']->id,
                            ]
                        ], Yii::$app->onesignal->app_parent);
                    }
                }
            }
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
        //$kid_id = $request->post('kid_id');
        $teacher_class = \common\models\TeacherClasses::findOne(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        // if ($kid_id) {
        //     $kid_class = \common\models\KidClasses::findOne(['class_id' => $class_id, 'kid_id' => $kid_id, 'status' => ACTIVE]);
        //     if (!$kid_class) {
        //         throw new \yii\web\BadRequestHttpException;
        //     }
        // }

        $class_id = $teacher_class->class->id;
        $school_id = $teacher_class->class->schoolGrade->school->id;
        $bucket = getenv('GCP_BUCKET_PRIVATE');
        $module = \Yii::$app->controller->module;
        $filepath = Yii::$app->gcs->getFilePath($school_id, $class_id, $module->id);
        $data = [
            'teacher_id' => $teacher_id,
            'class_id' => $class_id,
            'title' => $request->post('title'),
            'description' => $request->post('description'),
        ];

        if ($upload = Yii::$app->gcs->uploadBase64($bucket, $filepath, $filename, $base64, $data)) {
            // send notification
            $kid_class = \common\models\KidClasses::findAll(['class_id' => $class_id, 'status' => ACTIVE]);
            foreach ($kid_class as $kc) {
                $kid_name = $kc->kid->first_name . ' ' . $kc->kid->last_name;
                $parent_kids = \common\models\ParentKids::findAll(['kid_id' => $kc->kid_id, 'status' => ACTIVE]);
                if ($parent_kids) {
                    foreach ($parent_kids as $pk) {
                        // Yii::$app->onesignal->sendNotification($pk->parent_id, [
                        //     'content' => Yii::t('app/notification', '{kid} has new photo',['kid'=>$kid_name]),
                        //     'data' => [
                        //         'type' => 'photo',
                        //         'url' => $upload['url'],
                        //         'kid_id' => $kc->kid_id,
                        //         'photo_id' => $upload['photo']->id,
                        //     ]
                        // ], Yii::$app->onesignal->app_parent);

                        // push to redis list
                        $is_push = true;
                        $last_push = ParentPushNotifications::find()
                            ->where(['parent_id' => $pk->parent_id, 'push_type' => ParentPushNotifications::PUSH_TYPE_PHOTO])
                            ->orderBy('id desc')->one();
                        if ($last_push) {
                            // send push photo to parent only 1 time/day
                            $duration = time() - strtotime($last_push->pushed_at);
                            if ($duration < 24 * 3600) {
                                $is_push = false;
                            }
                        }

                        if ($is_push) {
                            $content = Yii::t('app/notification', '{kid} has new photo', ['kid' => $kid_name]);
                            $data = [
                                'type' => 'photo',
                                'url' => $upload['url'],
                                'kid_id' => $kc->kid_id,
                                'photo_id' => $upload['photo']->id,
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
                            $last_push->content_id = $upload['photo']->id;
                            $last_push->kid_id = $kc->kid_id;
                            $last_push->push_type = ParentPushNotifications::PUSH_TYPE_PHOTO;
                            $last_push->pushed_at = date('Y-m-d H:i:s');
                            $last_push->status = ACTIVE;
                            $last_push->save();
                        }
                    }
                }
            }
            return $upload;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }

    /** 
     * photo gallery
     * @param int $class_id
     */
    public function actionGallery()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $data = [];
        $data['albums'] = [];
        // get photos by album first
        $albums = Albums::find()
            ->where(['class_id' => $class_id, 'status' => ACTIVE])
            ->orderBy('id desc')->all();
        $photo_ids = [];
        foreach ($albums as $alb) {
            $alb_data = $alb->toArray();
            $alb_data['photos'] = [];
            //$alb_photos = $alb->albumPhotos;
            $alb_photos = AlbumPhotos::find()->where(['album_id' => $alb->id, 'status' => ACTIVE])->all();
            foreach ($alb_photos as $rp) {
                //$photo = $rp->toArray();
                if (empty($alb_data['photos'])) {
                    // get only 1 photos for each album
                    $photo = $rp->photo->toArray();
                    $photo['url'] = Yii::$app->gcs->getSignedUrl($rp->photo);
                    $alb_data['photos'][] = $photo;
                }
                $photo_ids[] = $rp->photo_id;
            }
            $data['albums'][] = $alb_data;
        }

        // get other photos
        $data['others'] = [];
        $other_photos = Photos::find()
            //->where(['teacher_id'=>$teacher_id,'class_id'=>$class_id,'status'=>ACTIVE, 'is_trivial' => Photos::NOT_TRIVIAL])
            ->where(['class_id' => $class_id, 'status' => ACTIVE, 'is_trivial' => Photos::NOT_TRIVIAL])
            ->andWhere(['not in', 'id', $photo_ids])
            ->orderBy('id desc')
            ->all();

        foreach ($other_photos as $op) {
            $created_at = new \DateTime($op->created_at);
            $month = $created_at->format('m');
            $year = $created_at->format('Y');

            if (!isset($data['others'][$month . '-' . $year])) {
                $data['others'][$month . '-' . $year] = [];
                $data['others'][$month . '-' . $year]['name'] = $month . '-' . $year;
                $data['others'][$month . '-' . $year]['month'] = $month;
                $data['others'][$month . '-' . $year]['year'] = $year;
                $data['others'][$month . '-' . $year]['photos'] = [];
                // get only 1 photo for each month
                $data_photo = $op->toArray();
                $data_photo['url'] = Yii::$app->gcs->getSignedUrl($op);
                $data['others'][$month . '-' . $year]['photos'][] = $data_photo;
            } else {
                //$data['others'][$month . '-' . $year]['photos'][] = $data_photo;
            }
        }

        return [
            'name' => 'photos gallery',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /**
     * create album
     * @param string $name
     * @param string $description
     * @param int $class_id
     */
    public function actionCreateAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $photo_ids = $request->post('photo_ids', null);
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $school_id = $teacher_class->class->schoolGrade->school->id;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $album = new Albums();
            $album->teacher_id = $teacher_id;
            $album->class_id = $class_id;
            $album->school_id = $school_id;
            $album->name = $request->post('name');
            $album->description = $request->post('description', null);
            $album->status = ACTIVE;

            if (!$album->save()) {
                throw new \yii\web\BadRequestHttpException("Save album to DB fail");
            }
            if ($photo_ids) {
                $photos = Photos::find()
                    ->where(['in', 'id', $photo_ids])->andWhere(['status' => ACTIVE])->all();
                if (!$photos) {
                    throw new \yii\web\BadRequestHttpException;
                }
                $album_photos = AlbumPhotos::find()->where(['album_id' => $album->id, 'status' => ACTIVE])->andWhere(['in', 'photo_id', $photo_ids])->all();
                $album_photo_ids = [];
                foreach ($album_photos as $ab) {
                    $album_photo_ids[] = $ab->photo_id;
                }
                foreach ($photos as $photo) {
                    if (!in_array($photo->id, $album_photo_ids)) {
                        $album_photo = new AlbumPhotos();
                        $album_photo->album_id = $album->id;
                        $album_photo->photo_id = $photo->id;
                        $album_photo->sequence = 1;
                        $album_photo->status = ACTIVE;
                        if (!$album_photo->save()) {
                            throw new \yii\web\BadRequestHttpException("Save album photo to DB fail");
                        }
                    }
                }
            }
            $transaction->commit();
            return [
                'name' => 'create album',
                'code' => 0,
                'status' => 200,
                'message' => 'success',
                'data' => $album
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => 'create album',
                'code' => -1,
                'status' => 400,
                'message' => 'error',
                'data' => $th->getMessage()
            ];
        }
    }

    /**
     * add photo to album
     * @param array $photos
     * @param int $album_id
     * @param int $class_id
     */
    public function actionAddPhotoAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $album_id = $request->post('album_id');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album || $album->class_id !== $class_id) {
            throw new \yii\web\BadRequestHttpException;
        }

        $photos = $request->post('photos');
        $data = [];
        foreach ($photos as $photo_id) {
            $photo = Photos::findOne(['id' => $photo_id, 'status' => ACTIVE]);
            if (!$photo) {
                throw new \yii\web\BadRequestHttpException;
            }
            $album_photo = AlbumPhotos::findOne(['album_id' => $album_id, 'photo_id' => $photo_id]);
            if (!$album_photo) {
                // insert
                $album_photo = new AlbumPhotos();
                $album_photo->album_id = $album_id;
                $album_photo->photo_id = $photo_id;
                $album_photo->sequence = 1;
                $album_photo->status = ACTIVE;
                if ($album_photo->save()) {
                } else {
                    return [
                        'name' => 'create photo album',
                        'code' => -1,
                        'status' => 500,
                        'message' => 'error',
                        'data' => $album_photo->errors,
                    ];
                }
            } else {
                // update
                $album_photo->status = ACTIVE;
                if ($album_photo->save()) {
                } else {
                    return [
                        'name' => 'create photo album',
                        'code' => -1,
                        'status' => 500,
                        'message' => 'error',
                        'data' => $album_photo->errors,
                    ];
                }
            }
            $data[] = $album_photo;
        }

        return [
            'name' => 'add photo to album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /** 
     * album detail
     * @param int $class_id
     * @param int $album_id
     * @param int $page
     */
    public function actionAlbumDetail()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $album_id = $request->post('album_id');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album || $album->class_id !== $class_id) {
            throw new \yii\web\BadRequestHttpException;
        }

        $page = $request->post('page', 1);

        $data = [];
        $data = $album->toArray();
        $data['page'] = $page;
        $data['photos'] = [];
        $data['count_photo'] = AlbumPhotos::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->count();
        $alb_photos = AlbumPhotos::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->orderBy('created_at desc')
            ->limit(AlbumPhotos::PAGE_SIZE)
            ->offset(($page - 1) * AlbumPhotos::PAGE_SIZE)
            ->all();
        foreach ($alb_photos as $rp) {
            //$photo = $rp->toArray();
            $photo = $rp->photo->toArray();
            $photo['url'] = Yii::$app->gcs->getSignedUrl($rp->photo);
            $data['photos'][] = $photo;
        }

        return [
            'name' => 'get album photos detail',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /**
     * photos month detail
     * @param int $month
     * @param int $year
     * @param int $class_id
     */
    public function actionPhotosMonth()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $month = $request->post('month');
        $year = $request->post('year');

        $page = $request->post('page', 1);

        $data = [];
        $data['month'] = $month;
        $data['year'] = $year;
        $data['photos'] = [];
        //$data['albums'] = [];
        // get photos by album first
        $albums = Albums::find()->where(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE])->orderBy('id desc')->all();
        $photo_ids = [];
        foreach ($albums as $alb) {
            //$alb_data = $alb->toArray();
            //$alb_data['photos'] = [];
            //$alb_photos = $alb->albumPhotos;
            $alb_photos = AlbumPhotos::find()->where(['album_id' => $alb->id, 'status' => ACTIVE])->all();
            foreach ($alb_photos as $rp) {
                //$photo = $rp->toArray();
                // if (empty($alb_data['photos'])) {
                //     // get only 1 photos for each album
                //     $photo = $rp->photo->toArray();
                //     $photo['url'] = Yii::$app->gcs->getSignedUrl($rp->photo);
                //     $alb_data['photos'][] = $photo;
                // }
                $photo_ids[] = $rp->photo_id;
            }

            //$data['albums'][] = $alb_data;
        }

        // get other photos
        //$data['others'] = [];
        $other_photos = Photos::find()
            //->where(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE, 'is_trivial' => Photos::NOT_TRIVIAL])
            ->where(['class_id' => $class_id, 'status' => ACTIVE, 'is_trivial' => Photos::NOT_TRIVIAL])
            ->andWhere(['=', "date_part('year',created_at)", $year])
            ->andWhere(['=', "date_part('month',created_at)", $month])
            ->andWhere(['not in', 'id', $photo_ids])
            ->limit(Photos::PAGE_SIZE)
            ->offset(($page - 1) * Photos::PAGE_SIZE)
            ->orderBy('id desc')
            ->all();

        foreach ($other_photos as $op) {
            $data_photo = $op->toArray();
            $data_photo['url'] = Yii::$app->gcs->getSignedUrl($op);
            $data['photos'][] = $data_photo;
        }

        return [
            'name' => 'get photos by month',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }





    /**
     * comment photo
     * @param int $photo_id
     * @param string $content
     */
    public function actionCommentPhoto()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $photo_id = $request->post('photo_id');
        $photo = Photos::findOne(['status' => ACTIVE, 'id' => $photo_id]);
        if (!$photo) {
            throw new \yii\web\BadRequestHttpException;
        }
        $content = $request->post('content');

        $comment = new PhotoComments();
        $comment->photo_id = $photo_id;
        $comment->teacher_id = $teacher_id;
        $comment->content = $content;
        $comment->status = ACTIVE;
        if ($comment->save()) {
        } else {
            return $comment->errors;
        }

        return [
            'name' => 'create comment photo',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $comment
        ];
    }

    /**
     * like photo
     * @param int $photo_id
     * @param int $status
     */
    public function actionLikePhoto()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $photo_id = $request->post('photo_id');
        $photo = Photos::findOne(['status' => ACTIVE, 'id' => $photo_id]);
        if (!$photo) {
            throw new \yii\web\BadRequestHttpException;
        }
        $status = $request->post('status', ACTIVE);

        $like = PhotoLikes::findOne(['photo_id' => $photo_id, 'teacher_id' => $teacher_id]);
        if (!$like) {
            $like = new PhotoLikes();
            $like->photo_id = $photo_id;
            $like->teacher_id = $teacher_id;
            $like->status = $status;
            if ($like->save()) {
            } else {
                return $like->errors;
            }
        } else {
            $like->status = $status;
            if ($like->save()) {
            } else {
                return $like->errors;
            }
        }

        return [
            'name' => 'create like photo',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $like
        ];
    }

    /** 
     * fetch comments photo
     * @param int $photo_id
     * @param int $page
     */
    public function actionFetchCommentPhoto()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $photo_id = $request->post('photo_id');
        $photo = Photos::findOne(['status' => ACTIVE, 'id' => $photo_id]);
        if (!$photo) {
            throw new \yii\web\BadRequestHttpException;
        }
        $page = $request->post('page', 1);

        $comments = PhotoComments::find()
            ->where(['photo_id' => $photo_id, 'status' => ACTIVE])
            ->orderBy('id DESC')
            ->limit(PhotoComments::PAGE_SIZE)
            ->offset(($page - 1) * PhotoComments::PAGE_SIZE)
            ->all();
        $data = [];
        foreach ($comments as $comment) {
            $comment_data = $comment->toArray();
            $comment_data['kid'] = $comment->kid;
            $comment_data['teacher'] = $comment->teacher;
            $data[] = $comment_data;
        }

        return [
            'name' => 'fetch comments photo',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /** 
     * fetch likes photo
     * @param int $photo_id
     * @param int $page
     */
    public function actionFetchLikePhoto()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $photo_id = $request->post('photo_id');
        $photo = Photos::findOne(['status' => ACTIVE, 'id' => $photo_id]);
        if (!$photo) {
            throw new \yii\web\BadRequestHttpException;
        }
        $page = $request->post('page', 1);

        $total = $likes = PhotoLikes::find()
            ->where(['photo_id' => $photo_id, 'status' => ACTIVE])
            ->count();

        $likes = PhotoLikes::find()
            ->where(['photo_id' => $photo_id, 'status' => ACTIVE])
            ->orderBy('id DESC')
            ->limit(PhotoLikes::PAGE_SIZE)
            ->offset(($page - 1) * PhotoLikes::PAGE_SIZE)
            ->all();
        $data = [];
        $data['total'] = $total;
        $data['likes'] = [];
        foreach ($likes as $like) {
            $like_data = $like->toArray();
            $like_data['kid'] = $like->kid;
            $like_data['teacher'] = $like->teacher;
            $data['likes'][] = $like_data;
        }

        return [
            'name' => 'fetch likes photo',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }






    //API New Album

    /** 
     * fetch comments album
     * @param int $album
     * @param int $page
     * @throws \yii\web\BadRequestHttpException if album not exits
     * @return array
     */
    public function actionFetchCommentAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $album_id = $request->post('album_id');
        $album = Albums::findOne(['status' => ACTIVE, 'id' => $album_id]);
        if (!$album) {
            throw new \yii\web\BadRequestHttpException('Album can not find');
        }
        $page = $request->post('page', 1);

        $comments = AlbumComments::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->orderBy('id DESC')
            ->limit(AlbumComments::PAGE_SIZE)
            ->offset(($page - 1) * AlbumComments::PAGE_SIZE)
            ->all();
        $data = [];
        $data['comments'] = [];
        $data['total_comment'] = AlbumComments::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->count();
        foreach ($comments as $comment) {
            $comment_data = $comment->toArray();
            $comment_data['kid'] = null;
            $comment_data['teacher'] = null;

            if ($comment->kid) {
                $comment_data['kid'] = $comment->kid->toArray();
                if ($comment->kid->photo) {
                    $data_kid_photo = $comment->kid->photo->toArray();
                    $data_kid_photo['url'] = Yii::$app->gcs->getSignedUrl($comment->kid->photo);
                    $comment_data['kid']['photo'] = $data_kid_photo;
                } else {
                    $comment_data['kid']['photo'] = null;
                }
            }

            if ($comment->teacher) {
                $comment_data['teacher'] = $comment->teacher->toArray();
                if ($comment->teacher->photo) {
                    $data_teacher_photo = $comment->teacher->photo->toArray();
                    $data_teacher_photo['url'] = Yii::$app->gcs->getSignedUrl($comment->teacher->photo);
                    $comment_data['teacher']['photo'] = $data_teacher_photo;
                } else {
                    $comment_data['teacher']['photo'] = null;
                }
            }
            $data['comments'][] = $comment_data;
        }

        return [
            'name' => 'fetch comments album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /** 
     * fetch likes album
     * @param int $album_id
     * @param int $page
     */
    public function actionFetchLikeAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $album_id = $request->post('album_id');
        $album = Albums::findOne(['status' => ACTIVE, 'id' => $album_id]);
        if (!$album) {
            throw new \yii\web\BadRequestHttpException('Album can not find');
        }
        $page = $request->post('page', 1);

        $check_like = AlbumLikes::find()
            ->where(['album_id' => $album_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])
            ->one();
        $likes = AlbumLikes::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->orderBy('id DESC')
            ->limit(AlbumLikes::PAGE_SIZE)
            ->offset(($page - 1) * AlbumLikes::PAGE_SIZE)
            ->all();
        $data = [];
        $data['likes'] = [];
        $data['is_like'] = 0;
        if ($check_like) {
            $data['is_like'] = 1;
        }
        $data['total_like'] = AlbumLikes::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->count();
        // foreach ($likes as $like) {
        //     $like_data = $like->toArray();
        //     if ($like->kid) {
        //         $like_data['kid'] = $like->kid->toArray();
        //         if ($like->kid->photo) {
        //             $data_kid_photo = $like->kid->photo->toArray();
        //             $data_kid_photo['url'] = Yii::$app->gcs->getSignedUrl($like->kid->photo);
        //             $like_data['kid']['photo'] = $data_kid_photo;
        //         }
        //     }
        //     if ($like->teacher) {
        //         $like_data['teacher'] = $like->teacher->toArray();
        //         if ($like->teacher->photo) {
        //             $data_kid_photo = $like->teacher->photo->toArray();
        //             $data_kid_photo['url'] = Yii::$app->gcs->getSignedUrl($like->teacher->photo);
        //             $like_data['kid']['photo'] = $data_kid_photo;
        //         }
        //     }
        //     $data['likes'][] = $like_data;
        // }

        return [
            'name' => 'fetch likes album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }



    /**
     * comment album
     * @param int $album_id
     * @param string $content
     */
    public function actionCommentAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $album_id = $request->post('album_id');
        $type = $request->post('type');
        $album = Albums::findOne(['status' => ACTIVE, 'id' => $album_id]);
        if (!$album) {
            throw new \yii\web\BadRequestHttpException;
        }
        $content = $request->post('content');
        $data = [];
        $data['comment'] = null;
        $data['type'] = null;
        $comment = new AlbumComments();
        $comment->album_id = $album_id;
        $comment->teacher_id = $teacher_id;
        $comment->content = $content;
        $comment->status = ACTIVE;
        if ($comment->save()) {
        } else {
            return [
                'name' => 'create comment photo',
                'code' => -1,
                'status' => 400,
                'message' => 'error',
                'data' => $comment->errors,
            ];
        }
        $data['comment'] = $comment;
        $data['type'] = $type;

        return [
            'name' => 'create comment album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /**
     * like album
     * @param int $album_id
     * @param int $status
     */
    public function actionLikeAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $album_id = $request->post('album_id');
        $album = Albums::findOne(['status' => ACTIVE, 'id' => $album_id]);
        $type = $request->post('type');
        if (!$album) {
            throw new \yii\web\BadRequestHttpException;
        }
        $status = $request->post('status', ACTIVE);
        $data = [];
        $data['like'] = null;
        $data['type'] = null;
        $like = AlbumLikes::findOne(['album_id' => $album_id, 'teacher_id' => $teacher_id]);
        if (!$like) {
            $like = new AlbumLikes();
            $like->album_id = $album_id;
            $like->teacher_id = $teacher_id;
            $like->status = $status;
            if ($like->save()) {
            } else {
                return [
                    'name' => 'create like album',
                    'code' => -1,
                    'status' => 400,
                    'message' => 'error',
                    'data' => $like->errors,
                ];
            }
        } else {
            $like->status = $status;
            if ($like->save()) {
            } else {
                return [
                    'name' => 'create like album',
                    'code' => -1,
                    'status' => 400,
                    'message' => 'error',
                    'data' => $like->errors,
                ];
            }
        }
        $data['like'] = $like;
        $data['type'] = $type;

        return [
            'name' => 'create like album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }
    /**
     * get list album
     * @param int $class_id
     * 
     */
    public function actionGetListAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $data = [];
        $albums = Albums::find()->where(['class_id' => $class_id, 'status' => ACTIVE])->orderBy('id desc')->all();
        // foreach ($albums as $album) {
        //     $data_album = $album->toArray();
        //     $data[]=$data_album;
        // }

        foreach ($albums as $alb) {
            $alb_data = $alb->toArray();
            $alb_data['photos'] = [];
            $count_photo = 0;
            //$alb_photos = $alb->albumPhotos;
            $alb_photos = AlbumPhotos::find()->where(['album_id' => $alb->id, 'status' => ACTIVE])->all();
            foreach ($alb_photos as $rp) {
                $count_photo++;
                //$photo = $rp->toArray();
                if (empty($alb_data['photos'])) {

                    // get only 1 photos for each album
                    $photo = $rp->photo->toArray();
                    $photo['url'] = Yii::$app->gcs->getSignedUrl($rp->photo);
                    $alb_data['photos'][] = $photo;
                }
                $photo_ids[] = $rp->photo_id;
            }
            $alb_data['count_photo'] = $count_photo;
            $data['albums'][] = $alb_data;
        }
        return [
            'name' => 'get list album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }


    /**
     * get list album
     * @param int $class_id
     * @param array $photo_id
     * @param int $album_id
     * @throws  \yii\web\BadRequestHttpException if teacher class not found
     */
    public function actionCreatePhotoAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $album_id = $request->post('album_id');
        $photo_ids = $request->post('photo_ids');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album) {
            throw new \yii\web\BadRequestHttpException('Album not found');
        }
        $photo = Photos::find()->where(['in', 'id', $photo_ids])
            ->andWhere(['status' => ACTIVE])->all();
        if (!$album) {
            throw new \yii\web\BadRequestHttpException('Album not found');
        }
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('teacher class not found');
        }
        $data = [];
        foreach ($photo_ids as $photo_id) {
            $album_photo = new AlbumPhotos();
            $album_photo->photo_id = $photo_id;
            $album_photo->album_id = $album_id;
            $album_photo->sequence = 1;
            $album_photo->status = ACTIVE;

            if ($album_photo->save()) {
            } else {
                return [
                    'name' => 'create photo album',
                    'code' => -1,
                    'status' => 500,
                    'message' => 'error',
                    'data' => $album_photo->errors,
                ];
            }
        }
        return [
            'name' => 'create photo album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $album_photo,
        ];
    }

    /**
     * @param int $class_id
     * @param int 
     */
    public function actionIndexAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $page = $request->post('page', 1);
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        //$school_id=$teacher_class->class->schoolGrade->school->id;
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('teacher class not found');
        }
        $data = [];
        // $data['albums'] = [];
        $album_classes = Albums::find()
            ->where(['class_id' => $class_id, 'status' => ACTIVE])
            ->orderBy('created_at desc')
            ->limit(Albums::PAGE_SIZE_ALBUM)
            ->offset(($page - 1) * Albums::PAGE_SIZE_ALBUM)
            ->all();
        foreach ($album_classes as $album) {

            $data_album = $album->toArray();
            $data_album['photos'] = [];

            $album_photos = AlbumPhotos::find()->where(['album_id' => $album->id, 'status' => ACTIVE])->orderBy('created_at desc')->limit(6)->all();
            //print_r($album_photos);die;
            $data_album['count_photo'] = AlbumPhotos::find()->where(['album_id' => $album->id, 'status' => ACTIVE])->count();

            foreach ($album_photos as $album_photo) {

                $data_photo = null;
                if ($album_photo->photo) {
                    $data_photo = $album_photo->photo->toArray();
                    $data_photo['url'] = Yii::$app->gcs->getSignedUrl($album_photo->photo);
                    $data_album['photos'][] = $data_photo;
                }
            }
            $data_album['count_comment'] = AlbumComments::find()->where(['album_id' => $album->id, 'status' => ACTIVE])->count();
            $data_album['count_like'] =  AlbumLikes::find()->where(['album_id' => $album->id, 'status' => ACTIVE])->count();
            $data_album['is_like'] = 0;
            $data_album['comment_news'] = [];

            //check is like

            $like = AlbumLikes::findOne(['album_id' => $album->id, 'teacher_id' => $teacher_id, 'status' => ACTIVE]);
            if ($like) {
                $data_album['is_like'] = 1;
            }



            //get 2 comment 
            $comments = AlbumComments::find()
                ->where(['album_id' => $album->id, 'status' => ACTIVE])
                ->orderBy('id DESC')
                ->limit(AlbumComments::COMMENT_INDEX)
                ->all();
            foreach ($comments as $comment) {
                $comment_data = $comment->toArray();
                $comment_data['kid'] = null;
                $comment_data['teacher'] = null;

                if ($comment->kid) {
                    $comment_data['kid'] = $comment->kid->toArray();
                    if ($comment->kid->photo) {
                        $data_kid_photo = $comment->kid->photo->toArray();
                        $data_kid_photo['url'] = Yii::$app->gcs->getSignedUrl($comment->kid->photo);
                        $comment_data['kid']['photo'] = $data_kid_photo;
                    } else {
                        $comment_data['kid']['photo'] = null;
                    }
                }

                if ($comment->teacher) {
                    $comment_data['teacher'] = $comment->teacher->toArray();
                    if ($comment->teacher->photo) {
                        $data_teacher_photo = $comment->teacher->photo->toArray();
                        $data_teacher_photo['url'] = Yii::$app->gcs->getSignedUrl($comment->teacher->photo);
                        $comment_data['teacher']['photo'] = $data_teacher_photo;
                    } else {
                        $comment_data['teacher']['photo'] = null;
                    }
                }
                $data_album['comment_news'][] = $comment_data;
            }


            $data[] = $data_album;
        }

        return [
            'name' => 'index photo album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data,
        ];
    }
    /**
     * @param int $class_id
     * @param int 
     */
    public function actionDetailAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }

        $album_id = $request->post('album_id');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album || $album->class_id !== $class_id) {
            throw new \yii\web\BadRequestHttpException;
        }

        $page = $request->post('page', 1);

        $data = [];
        $data = $album->toArray();
        $data['page'] = $page;
        $data['photos'] = [];
        $data['count_photo'] =
            AlbumPhotos::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->count();

        $alb_photos = AlbumPhotos::find()
            ->where(['album_id' => $album_id, 'status' => ACTIVE])
            ->orderBy('created_at desc')
            ->limit(AlbumPhotos::PAGE_SIZE)
            ->offset(($page - 1) * AlbumPhotos::PAGE_SIZE)
            ->all();
        foreach ($alb_photos as $rp) {
            //$photo = $rp->toArray();
            $photo = $rp->photo->toArray();
            $photo['url'] = Yii::$app->gcs->getSignedUrl($rp->photo);
            $data['photos'][] = $photo;
        }

        return [
            'name' => 'get album photos detail',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /**
     * @param int $class_id
     * @param int 
     */
    public function actionDeletePhotoAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $data = [];
        $album_id = $request->post('album_id');
        $photo_ids = $request->post('photo_ids');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album || $album->class_id !== $class_id) {
            throw new \yii\web\BadRequestHttpException;
        }
        $album_photos = AlbumPhotos::find()->where(['album_id' => $album_id])
            ->andWhere(['in', 'photo_id', $photo_ids])
            ->all();
        foreach ($album_photos as $photo) {
            if ($photo->delete()) {
            } else {
                return [
                    'name' => 'delete photo album',
                    'code' => -1,
                    'status' => 300,
                    'message' => 'error',
                    'data' => $photo->errors
                ];
            }
        }
        return [
            'name' => 'delete photo album',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    /**
     * @param int class_id
     * @param int album_id
     */
    public function actionDeleteAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('teacher class not found');
        }
        $album_id = $request->post('album_id');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album || $album->class_id !== $class_id) {
            throw new \yii\web\BadRequestHttpException;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($photos = $album->albumPhotos) {
                foreach ($photos as $ap) {
                    $ap->delete();
                }
            }
            if ($comments = $album->albumComments) {
                foreach ($comments as $ac) {
                    $ac->delete();
                }
            }
            if ($likes = $album->albumLikes) {
                foreach ($likes as $al) {
                    $al->delete();
                }
            }
            $album->delete();
            $transaction->commit();
            return [
                'name' => "delete album",
                'code' => 0,
                'status' => 200,
                "message" => 'success',
                'data' => $album
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "delete album",
                'code' => -1,
                'status' => 500,
                "message" => 'errors',
                'data' => $th->getMessage(),
            ];
        }
    }

    /**
     * @param int class_id
     * @param int album_id
     */
    public function actionUpdateAlbum()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = TeacherClasses::findOne(['teacher_id' => $teacher_id, 'class_id' => $class_id, 'status' => ACTIVE]);
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('teacher class not found');
        }
        $album_id = $request->post('album_id');
        $album_name = $request->post('album_name');
        $album = Albums::findOne(['id' => $album_id, 'status' => ACTIVE]);
        if (!$album || $album->class_id !== $class_id) {
            throw new \yii\web\BadRequestHttpException;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $album->name = $album_name;
            $album->save();
            $transaction->commit();
            return [
                'name' => "update album",
                'code' => 0,
                'status' => 200,
                "message" => 'success',
                'data' => $album
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "update album",
                'code' => -1,
                'status' => 500,
                "message" => 'errors',
                'data' => $th->getMessage(),
            ];
        }
    }
}
