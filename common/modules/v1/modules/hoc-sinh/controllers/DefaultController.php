<?php

namespace common\modules\v10\modules\customer\controllers;

use yii\web\Controller;
use Yii;
use common\models\Classes;
use common\models\TeacherClasses;
use common\models\Kids;
use common\models\KidClasses;
use common\models\Parents;
use common\models\ParentKids;
use common\models\SchoolKids;
use common\helpers\PhoneHelper;
use common\helpers\NameHelper;
use common\models\ParentKidPackageRoles;
use common\models\ParentPushNotifications;
use common\models\ParentSettings;

/**
 * Default controller for the `customer` module
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
     * get list kid in class
     * 
     * @param int $class_id
     * 
     * @throws \yii\web\BadRequestHttpException if teacher class not found 
     * @return mixed
     */
    public function actionGetKidIndex()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $page = $request->post('page', 1);
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $list_kid_ids = [];
        $data = [];
        $count = 0;
        $count =  $kids = Kids::find()
            ->joinWith('kidClasses')
            ->where(['kid_classes.class_id' => $class_id, 'kid_classes.status' => ACTIVE, 'kids.status' => ACTIVE])
            ->count();

        $kids = Kids::find()
            ->joinWith('kidClasses')
            ->where(['kid_classes.class_id' => $class_id, 'kid_classes.status' => ACTIVE, 'kids.status' => ACTIVE])
            ->orderBy(['last_name' => SORT_ASC, 'first_name' => SORT_ASC])
            ->limit(Kids::PAGE_SIZE)
            ->offset(Kids::PAGE_SIZE * ($page - 1))
            ->all();
        foreach ($kids as $kid) {
            $data_kid = $kid->toArray();
            if ($photo = $kid->photo) {
                $data_kid['photo']['id'] = $photo->id;
                $data_kid['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo);
            } else {
                $data_kid['photo'] = null;
            }
            $data[] = $data_kid;
        }

        return [
            'name' => "Get list kid in class",
            'code' => 0,
            'status' => 200,
            'message' => "success",
            'data' => $data,
            'count' => $count,
            'page' => $page
        ];
    }

    /**
     * View detail kid
     * 
     * @param int $class_id
     * @param int $kid_id
     * @throws \yii\web\BadRequestHttpException if teacher class not found 
     * @return mixed
     */
    public function actionDetailKid()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $kid_id = $request->post('kid_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found teacher class');
        }
        $school = $teacher_class->class->schoolGrade->school;
        $school_kid = SchoolKids::find()->where(['school_id' => $school->id, 'kid_id' => $kid_id, 'status' => ACTIVE])->one();
        if (!$school_kid) {
            throw new \yii\web\BadRequestHttpException('not found school kid');
        }
        $data = [];
        $kid_class = KidClasses::find()->joinWith('kid')
            ->where([
                'kid_id' => $kid_id,
                'class_id' => $class_id,
                'kid_classes.status' => ACTIVE,
                'kids.status' => ACTIVE
            ])
            ->one();
        if (!$kid_class) {
            throw new \yii\web\BadRequestHttpException('not found kid class');
        }
        $data_kid = $kid_class->kid;
        $kid_data = $data_kid->toArray();
        $kid_data['entered_at'] = $school_kid->entered_at;
        if ($photo_kid = $data_kid->photo) {
            $kid_data['photo']['id'] = $photo_kid->id;
            $kid_data['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo_kid);
        } else {
            $kid_data['photo'] = null;
        }
        $data['kid'] = $kid_data;
        $data['parents'] = [];
        if ($data_parents = $data_kid->parentKids) {
            foreach ($data_parents as $parent_kid) {
                $parent = $parent_kid->parent;
                if ($parent->status !== ACTIVE) continue;
                unset($parent->password);
                unset($parent->is_temporary_password);
                unset($parent->temp_password);
                unset($parent->created_at);
                unset($parent->updated_at);
                $parent_array = $parent->toArray();
                $parent_array['relationship'] = $parent_kid->relation;
                if ($photo_parent = $parent->photo) {
                    $parent_array['photo']['id'] = $photo_parent->id;
                    $parent_array['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo_parent);
                } else {
                    $parent_array['photo'] = null;
                }
                $data['parents'][] = $parent_array;
            }
        }
        return [
            'name' => 'detail kids',
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    public static function AddParent($kid_id, $parent, $school, $school_kid_id, $address = null)
    {
        $errors = null;
        // $transaction_parent = Yii::$app->db->beginTransaction();
        try {
            $exist_parent = Parents::findOne([
                'phone_number' => PhoneHelper::sanitizePrefix((string) ($parent['phone_number'])),
                'status' => ACTIVE
            ]);
            if (!$exist_parent) {
                $parent_model = new Parents();
                $password = PhoneHelper::getPrefix($parent['phone_numbe']);
                $parent_model->setTempPassword($password);
                $name = NameHelper::splitName($parent['full_name']);
                $parent_model->first_name = $name['first_name'];
                $parent_model->last_name = $name['last_name'];
                $parent_model->phone_number = PhoneHelper::sanitizePrefix((string) ($parent['phone_number']));
                $parent_model->gender = ($parent['gender'] == 1) ? 1 : 2;
                $parent_model->address = $address;
                $parent_model->photo_id = 4;
                if (!$parent_model->save()) {
                    $errors = $parent_model->errors;
                    throw new \yii\web\BadRequestHttpException('create parent failed');
                }

                $parent_kid = new ParentKids([
                    'parent_id' => $parent->id,
                    'kid_id' => $kid_id,
                    'status' => ACTIVE,
                ]);
                if (!$parent_kid->save()) {
                    $errors = $parent_kid->errors;
                    throw new \yii\web\BadRequestHttpException('create parent failed');
                }

                // RBAC assignment
                Yii::$app->authrbac->rbacAssignmentParent($school->id, $parent->id, $school_kid_id);
            } else {
                // parent exist, only add parent kids
                $parent_kid = new ParentKids([
                    'parent_id' => $exist_parent->id,
                    'kid_id' => $kid_id,
                    'status' => ACTIVE,
                ]);
                if (!$parent_kid->save()) {
                    $errors = $parent_kid->errors;
                    throw new \yii\web\BadRequestHttpException('create parent failed');
                }

                // RBAC assignment

                Yii::$app->authrbac->rbacAssignmentParent($school->id, $exist_parent->id, $school_kid_id);
            }
            // $transaction_parent->commit();
            return [
                'is_error' => 0,
                'data' => $parent_model
            ];
        } catch (\Throwable $th) {
            // $transaction_parent->rollBack();
            return [
                'is_error' => 0,
                'data' => $errors
            ];
        }
    }


    public function actionAddParent()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $kid_id = $request->post('kid_id');
        $parent = $request->post('parent');
        $teacher_class = TeacherClasses::find()->where(['class_id' => $class_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $class = Classes::find()->where(['id' => $class_id, 'status' => ACTIVE])->one();
        if (!$class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $school = $class->schoolGrade->school;
        $school_kid = SchoolKids::find()->where(['school_id' => $school->id, 'kid_id' => $kid_id, 'status' => ACTIVE])->one();
        if (!$school_kid) {
            throw new \yii\web\BadRequestHttpException('school kid not found');
        }
        $errors = null;
        $relation = null;
        if (isset($parent['relationship'])) {
            $relation = $parent['relationship'];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // $data_return=$this->AddParent($kid_id,$parent,$school,$school_kid->id,$parent['address']);
            $exist_parent = Parents::findOne([
                'phone_number' => PhoneHelper::sanitizePrefix((string) ($parent['phone_number'])),
                'status' => ACTIVE
            ]);
            $data_retun = null;
            if (!$exist_parent) {
                $parent_model = new Parents();
                $password = PhoneHelper::getPrefix($parent['phone_number']);
                $parent_model->setTempPassword($password);
                $name = NameHelper::splitName($parent['full_name']);
                $parent_model->first_name = $name['first_name'];
                $parent_model->last_name = $name['last_name'];
                $parent_model->phone_number = PhoneHelper::sanitizePrefix((string) ($parent['phone_number']));
                $parent_model->gender = ($parent['gender'] == 1) ? 1 : 2;
                $parent_model->photo_id = 4;
                $parent_model->address = $parent['address'];
                $parent_model->professional_id = $parent['job'];
                $password = PhoneHelper::getPrefix($parent['phone_number']);
                $parent_model->status = ACTIVE;
                $parent_model->setTempPassword($password);
                if (!$parent_model->save()) {
                    $errors = $parent_model->errors;
                    throw new \yii\web\BadRequestHttpException('create parent failed');
                }
                $parent_kid = new ParentKids([
                    'parent_id' => $parent_model->id,
                    'kid_id' => $kid_id,
                    'status' => ACTIVE,
                    "relation" => $relation,
                ]);
                if (!$parent_kid->save()) {
                    $errors = $parent_kid->errors;
                    throw new \yii\web\BadRequestHttpException('create parent failed');
                }
                // RBAC assignment
                $data_retun = $parent_model;
                Yii::$app->authrbac->rbacAssignmentParent($school->id, $data_retun->id, $school_kid->id);
            } else {
                // parent exist, only add parent kids
                $data_retun = $exist_parent;
                $check_parent_kid = ParentKids::find()->where(['parent_id' => $exist_parent->id, 'kid_id' => $kid_id, 'status' => ACTIVE])->one();
                if (!$check_parent_kid) {
                    $parent_kid = new ParentKids([
                        'parent_id' => $exist_parent->id,
                        'kid_id' => $kid_id,
                        'status' => ACTIVE,
                    ]);
                } else {
                    $parent_kid = $check_parent_kid;
                }
                $parent_kid->relation = $relation;
                if (!$parent_kid->save()) {
                    $errors = $parent_kid->errors;
                    throw new \yii\web\BadRequestHttpException('create parent failed');
                }
                // RBAC assignment
                Yii::$app->authrbac->rbacAssignmentParent($school->id, $data_retun->id, $school_kid->id);
            }
            $transaction->commit();

            if (isset($data_retun->is_temporary_password)) {
                unset($data_retun->is_temporary_password);
            }
            if (isset($data_retun->temp_password)) {
                unset($data_retun->temp_password);
            }
            if (isset($data_retun->created_at)) {
                unset($data_retun->created_at);
            }
            if (isset($data_retun->updated_at)) {
                unset($data_retun->updated_at);
            }
            return [
                'name' => "add parent ",
                'code' => 0,
                'status' => 200,
                "message" => 'success',
                'data' => $data_retun,
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "add parent ",
                'code' => -1,
                'status' => 500,
                "message" => 'errors',
                'data' => $th->getMessage(),
            ];
        }
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
    public function actionAddKid()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('teacher class not found');
        }
        $class = Classes::find()->where(['id' => $class_id, 'status' => ACTIVE])->one();
        if (!$class) {
            throw new \yii\web\BadRequestHttpException('class not found');
        }
        $errors = null;

        $school = $class->schoolGrade->school;
        $data_kid = $request->post('kid');
        if (!$data_kid['full_name'] || !$data_kid['dob'] || !$data_kid['gender']) {
            throw new \yii\web\BadRequestHttpException('Thiếu dữ liệu học sinh');
        }
        $data_parent = $request->post('parents');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $kid = new Kids();
            $name = NameHelper::splitName($data_kid['full_name']);
            $kid->first_name = $name['first_name'];
            $kid->last_name = $name['last_name'];
            $kid->dob = date('Y-m-d', strtotime($data_kid['dob']));
            $kid->gender = ($data_kid['gender'] == 1) ? 1 : 2;
            $kid->photo_id = ($data_kid['gender'] == 1) ? Kids::MALE_DEFAULT_PHOTO_ID : Kids::FEMALE_DEFAULT_PHOTO_ID;
            $kid->status = 1;
            if (!$kid->save()) {
                $errors = $kid->errors;
            } else {
                $school_kid = new SchoolKids([
                    'school_id' => $school->id,
                    'kid_id' => $kid->id,
                    'status' => ACTIVE,
                ]);
                if (!$school_kid->save()) {
                    $errors = $school_kid->errors;
                    throw new \yii\base\Exception('save school kid failed');
                }

                $kid_class = new KidClasses([
                    'class_id' => $class_id,
                    'kid_id' => $kid->id,
                    'status' => ACTIVE,
                    'entered_at' => Date('Y-m-d'),
                ]);
                if (!$kid_class->save()) {
                    $errors = $kid_class->errors;
                    throw new \yii\base\Exception('save kid class failed');
                }
                foreach ($data_parent as $parent) {
                    $relation = null;
                    if (isset($parent['relationship'])) {
                        $relation = $parent['relationship'];
                    }
                    $exist_parent = Parents::findOne([
                        'phone_number' => PhoneHelper::sanitizePrefix((string) ($parent['phone_number'])),
                        'status' => ACTIVE
                    ]);
                    $data_retun = null;
                    if (!$exist_parent) {
                        $parent_model = new Parents();
                        $password = PhoneHelper::getPrefix($parent['phone_number']);
                        $parent_model->setTempPassword($password);
                        $name = NameHelper::splitName($parent['full_name']);

                        $parent_model->first_name = $name['first_name'];
                        $parent_model->last_name = $name['last_name'];
                        $parent_model->phone_number = PhoneHelper::sanitizePrefix((string) ($parent['phone_number']));
                        $parent_model->gender = ($parent['gender'] == 1) ? 1 : 2;
                        $parent_model->photo_id = 4;
                        $parent_model->address = (!$parent['address']) ? $data_kid['address'] : $parent['address'];
                        $parent_model->professional_id = $parent['job'];
                        $password = PhoneHelper::getPrefix($parent['phone_number']);
                        $parent_model->status = ACTIVE;
                        $parent_model->setTempPassword($password);

                        if (!$parent_model->save()) {
                            $errors = $parent_model->errors;
                            throw new \yii\web\BadRequestHttpException('create parent failed');
                        }

                        $parent_kid = new ParentKids([
                            'parent_id' => $parent_model->id,
                            'kid_id' => $kid->id,
                            'status' => ACTIVE,
                            "relation" => $relation,
                        ]);

                        if (!$parent_kid->save()) {
                            throw new \yii\web\BadRequestHttpException('create parent failed');
                        }

                        // RBAC assignment
                        $data_retun = $parent_model;
                        Yii::$app->authrbac->rbacAssignmentParent($school->id, $data_retun->id, $school_kid->id);
                    } else {

                        // parent exist, only add parent kids

                        $check_parent_kid = ParentKids::find()->where(['parent_id' => $exist_parent->id, 'kid_id' => $kid->id, 'status' => ACTIVE])->one();
                        if (!$check_parent_kid) {
                            $parent_kid = new ParentKids([
                                'parent_id' => $exist_parent->id,
                                'kid_id' => $kid->id,
                                'status' => ACTIVE,
                            ]);
                        } else {
                            $parent_kid = $check_parent_kid;
                        }
                        $parent_kid->relation = $relation;
                        if (!$parent_kid->save()) {
                            throw new \yii\web\BadRequestHttpException('create parent failed');
                        }
                        // RBAC assignment
                        $data_retun = $exist_parent;
                        Yii::$app->authrbac->rbacAssignmentParent($school->id, $data_retun->id, $school_kid->id);
                    }
                }
            }
            $transaction->commit();
            return [
                'name' => "add kid ",
                'code' => 0,
                'status' => 200,
                "message" => 'success',
                'data' => $kid,
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "add kid ",
                'code' => -1,
                'status' => 500,
                "message" => 'errors',
                'data' => $th->getMessage(),
            ];
        }
    }

    public function actionUpdateKid()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $kid_request = $request->post('kid');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('Not found teacher class');
        }
        $school = $teacher_class->class->schoolGrade->school;
        if (!isset($kid_request['id'])) {
            throw new \yii\web\BadRequestHttpException('Not found kid');
        }
        $school_kid = SchoolKids::find()->where(['school_id' => $school->id, 'kid_id' => $kid_request['id'], 'status' => ACTIVE])->one();
        if (!$school_kid) {
            throw new \yii\web\BadRequestHttpException('Not found school kid');
        }
        $errors = null;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $kid = Kids::find()->where(['id' => $kid_request['id'], 'status' => ACTIVE])->one();
            if (!$kid) {
                throw new \yii\web\BadRequestHttpException('Not found kid');
            }

            $name = NameHelper::splitName($kid_request['full_name']);
            $kid->first_name = $name['first_name'];
            $kid->last_name = $name['last_name'];
            $kid->dob = date('Y-m-d', strtotime($kid_request['dob']));
            $kid->gender = $kid_request['gender'];
            if (($kid->photo_id == Kids::FEMALE_DEFAULT_PHOTO_ID) || ($kid->photo_id == Kids::MALE_DEFAULT_PHOTO_ID)) {
                $kid->photo_id = ($kid->gender == 1) ? Kids::MALE_DEFAULT_PHOTO_ID : Kids::FEMALE_DEFAULT_PHOTO_ID;
            }
            if (!$kid->save()) {
                $errors = $kid->errors;
                throw new \yii\base\Exception('save kid class failed');
            }

            if (isset($kid_request['entered_at'])) {
                $school_kid->entered_at = date('Y-m-d', strtotime($kid_request['entered_at']));
                if (!$school_kid->save()) {
                    $errors = $school_kid->errors;
                    throw new \yii\base\Exception('save kid class failed');
                }
            }
            $transaction->commit();
            return [
                'name' => "update kid",
                'code' => 0,
                'status' => 200,
                'message' => 'success',
                'data' => $kid,
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "Update kid",
                'code' => -1,
                'status' => 500,
                'message' => 'error',
                'data' => $th->getMessage(),
            ];
        }
    }



    public function actionUpdateParent()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $parent_request = $request->post('parent');
        $kid_id = $request->post('kid_id', null);
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found kid class');
        }
        $parent = Parents::find()->where(['id' => $parent_request['id'], 'status' => ACTIVE])->one();
        if (!$parent) {
            throw new \yii\web\BadRequestHttpException('not found parent');
        }
        $parent_kid = ParentKids::find()->where(['parent_id' => $parent_request['id'], 'kid_id' => $kid_id, 'status' => ACTIVE])->one();
        $errors = null;
        $relation = null;
        if (isset($parent_request['relationship'])) {
            $relation = $parent_request['relationship'];
        }
        $exist_parent = Parents::find()
            ->where([
                'phone_number' => PhoneHelper::sanitizePrefix((string) ($parent_request['phone_number']))
            ])
            ->andWhere(['!=', 'phone_number', $parent->phone_number])->one();
        if ($exist_parent) {
            return [
                'name' => "update parent ",
                "code" => -1,
                "status" => 501,
                "message" => "Số điện thoại đã tồn tại"
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $name = NameHelper::splitName($parent_request['full_name']);
            $parent->first_name = $name['first_name'];
            $parent->last_name = $name['last_name'];
            $parent->gender = $parent_request['gender'];
            $parent->professional_id = $parent_request['job'];
            $parent->address = $parent_request['address'];
            if ($parent->phone_number != PhoneHelper::sanitizePrefix((string)($parent_request['phone_number']))) {
                if ($parent->is_temporary_password == 1) {
                    $password = PhoneHelper::getPrefix($parent_request['phone_number']);
                    $parent->setTempPassword($password);
                }
                $parent->phone_number = PhoneHelper::sanitizePrefix((string) ($parent_request['phone_number']));
            }

            if (!$parent->save()) {
                throw new \yii\base\Exception('save parent failed');
            }
            if ($parent_kid) {
                $parent_kid->relation = $relation;
                if (!$parent_kid->save()) {
                    throw new \yii\base\Exception('save parent failed');
                }
            }

            $transaction->commit();
            if (isset($parent->is_temporary_password)) {
                unset($parent->is_temporary_password);
            }
            if (isset($parent->temp_password)) {
                unset($parent->temp_password);
            }
            if (isset($parent->password)) {
                unset($parent->password);
            }
            return [
                'name' => "update parent",
                "code" => 0,
                "status" => 200,
                "message" => "success",
                "data" => $parent
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "Update parent",
                'code' => -1,
                'status' => 500,
                'message' => 'error',
                'data' => $th->getMessage(),
            ];
        }
    }





    public function actionGetListKid()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found kid class');
        }
        $list_ids = [];
        $data = [];
        $kid_classes = KidClasses::find()
            ->joinWith(['kid'])
            ->where(['class_id' => $class_id, 'kid_classes.status' => ACTIVE, 'kids.status' => ACTIVE])
            ->orderBy(['kids.last_name' => SORT_ASC, 'kids.first_name' => SORT_ASC])
            ->all();
        foreach ($kid_classes as $kid_class) {
            if (in_array($kid_class->kid_id, $list_ids)) continue;
            $data_kid = $kid_class->kid->toArray();
            if ($photo = $kid_class->kid->photo) {
                $data_kid['photo']['id'] = $photo->id;
                $data_kid['photo']['url'] = Yii::$app->gcs->getSignedUrl($photo);
            } else {
                $data_kid['photo'] = null;
            }
            $data[] = $data_kid;
            $list_ids[] = $kid_class->id;
        }
        return [
            'name' => "Get list kid",
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    public function actionGetListClass()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found kid class');
        }
        $class = Classes::find()->where(['id' => $class_id, 'status' => ACTIVE])->one();
        if (!$class) {
            throw new \yii\web\BadRequestHttpException('not found class');
        }
        $data = [];
        $school = $class->schoolGrade->school;
        $list_classes = Classes::find()
            ->joinWith('schoolGrade')
            ->where([
                'classes.status' => ACTIVE,
                'school_grades.status' => ACTIVE,
                'school_grades.school_id' => $school->id
            ])
            ->andWhere(['!=', 'classes.id', $class_id])
            ->all();
        foreach ($list_classes as $cl) {
            $data[] = $cl->toArray();
        }
        return [
            'name' => "Get list class in school",
            'code' => 0,
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ];
    }

    public function actionChangeClass()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $class_id_to = $request->post('class_id_to');
        $kid_ids = $request->post('kid_ids');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException;
        }
        $now_date = date('Y-m-d');
        $kid_classes = KidClasses::find()->where(['class_id' => $class_id, 'status' => ACTIVE])
            ->andWhere(['in', 'kid_id', $kid_ids])
            ->all();
        $errors = null;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($kid_classes as $kc) {
                $kc->status = INACTIVE;
                $kc->exited_at = $now_date;
                if (!$kc->save()) {
                    $errors = $kc->errors;
                    throw new \yii\base\Exception('change class failed');
                }
            }

            foreach ($kid_ids as $kid_id) {
                $kid_class = new KidClasses();
                $kid_class->kid_id = $kid_id;
                $kid_class->class_id = $class_id_to;
                $kid_class->entered_at = $now_date;
                $kid_class->status = ACTIVE;
                if (!$kid_class->save()) {
                    $errors = $kid_class->errors;
                    throw new \yii\base\Exception('change class fail');
                }
            }

            $transaction->commit();
            return [
                'name' => "change class",
                'code' => 0,
                "status" => 200,
                "massage" => 'success',
                'data' => $kid_class
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "change class",
                'code' => -1,
                "status" => 500,
                "massage" => 'errors',
                'data' => $th->getMessage()
            ];
        }
    }
    /**
     * Deletes an existing Kids model.
     * Use only add wrong kid
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteKid()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $id = $request->post('kid_id');
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found teacher class');
        }
        $class = $teacher_class->class;
        if ((!$class) || ($class->status != ACTIVE)) {
            throw new \yii\web\BadRequestHttpException('not found  class');
        }
        $kid = Kids::find()->where(['id' => $id, 'status' => ACTIVE])->one();
        if (!$kid) {
            throw new \yii\web\BadRequestHttpException('not found  kid');
        }
        $school = $class->schoolGrade->school;
        $parent_kids = ParentKids::findAll(['kid_id' => $id]);
        $parent_ids = [];
        foreach ($parent_kids as $p) {
            $parent_ids[] = $p->parent_id;
        }

        $school_kid = SchoolKids::findOne(['school_id' => $school->id, 'kid_id' => $id]);
        $has_other_kids = ParentKids::find()
            ->where(['in', 'parent_id', $parent_ids])
            ->andWhere(['!=', 'kid_id', $id])->all();

        $school_kid_id = $school_kid->id;
        $errors = null;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            ParentPushNotifications::deleteAll(['kid_id' => $id]);
            foreach ($parent_kids as $p) {
                if (!$p->delete()) {
                    $errors = $p->errors;
                    throw new \yii\base\Exception();
                }
            }

            foreach ($parent_ids as $parent_id) {
                $is_has = false;
                foreach ($has_other_kids as $has) {
                    if ($has->parent_id == $parent_id) {
                        $is_has = true;
                        break;
                    }
                }
                if ($is_has == false) {
                    ParentPushNotifications::deleteAll(['parent_id' => $parent_id]);
                    ParentSettings::deleteAll(['parent_id' => $parent_id]);
                    $parent = Parents::findOne(['id' => $parent_id]);
                    if (!$parent->delete()) {
                        $errors = $parent->errors;
                        throw new \yii\base\Exception();
                    }
                }
                // rbac revoke
                // die;
                Yii::$app->authrbac->rbacRevokeParent($school->id, $parent_id, $school_kid_id);
            }

            if ($kid_classes = $kid->kidClasses) {
                foreach ($kid_classes as $kid_class) {
                    if (!$kid_class->delete()) {
                        $errors = $kid_class->errors;
                        throw new \yii\base\Exception();
                    }
                }
            }
            if ($school_kids = $kid->schoolKids) {
                foreach ($school_kids as $school_kid) {
                    if (!$school_kid->delete()) {
                        $errors = $school_kid->errors;
                        throw new \yii\base\Exception();
                    }
                }
            }

            $kid->status = INACTIVE;
            if (!$kid->save()) {
                throw new \yii\base\Exception();
                $errors = $kid->errors;
            }

            $transaction->commit();
            return [
                'name' => 'delete kid',
                'code' => 0,
                'status' => 200,
                'message' => 'success',
                'data' => $kid
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "delete kid",
                'code' => -1,
                'status' => 500,
                'message' => 'error',
                'data' => $th->getMessage()
            ];
        }
    }
    /**
     * Deletes an existing Kids model.
     * Use only add wrong kid
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteParent()
    {
        $request = Yii::$app->request;
        $teacher_id = Yii::$app->user->identity->id;
        $class_id = $request->post('class_id');
        $kid_id = $request->post('kid_id');
        $parent_id = $request->post('parent_id');
        $parent  = Parents::findOne(['id' => $parent_id]);
        if (!$parent) {
            throw new \yii\web\BadRequestHttpException('not found parent ');
        }
        $teacher_class = \common\models\TeacherClasses::find()->where(['class_id' => $class_id, 'teacher_id' => $teacher_id, 'status' => ACTIVE])->one();
        if (!$teacher_class) {
            throw new \yii\web\BadRequestHttpException('not found teacher class');
        }
        $errors = null;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            ParentKids::deleteAll(['parent_id' => $parent_id, 'kid_id' => $kid_id]);
            ParentKidPackageRoles::deleteAll(['parent_id' => $parent_id, 'kid_id' => $kid_id]);

            $has_other_kids = ParentKids::find()->where(['parent_id' => $parent_id])->andWhere(['!=', 'kid_id', $kid_id])->one();
            if (!$has_other_kids) {
                ParentPushNotifications::deleteAll(['parent_id' => $parent_id]);
                ParentSettings::deleteAll(['parent_id' => $parent_id]);
                if (!$parent->delete()) {

                    $errors = $parent->errors;
                    throw new \yii\base\Exception();
                }
            }
            $transaction->commit();
            return [
                'name' => "delete parent",
                'code' => 0,
                'status' => 200,
                'message' => 'success',
                'data' => null
            ];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [
                'name' => "delete parent",
                "code" => -1,
                "status" => 500,
                'message' => "errors",
                'data' => $th->getMessage(),
            ];
        }
    }
}
