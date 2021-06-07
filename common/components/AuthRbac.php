<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\ParentKidPackageRoles;
use common\models\TeacherPackageRoles;
use common\models\PrincipalPackageRoles;
use common\models\SchoolKids;
use common\models\SchoolTeachers;
use common\models\PrincipalSchools;

class AuthRbac extends Component
{
    const IS_DEFAULT = 1;

    /**
     * RBAC assignment
     * grant parent app permission
     * @param int $school_id
     * @param int $teacher_id
     * @param int $school_teacher_id
     * @param string $role_name
     * @return true if assignment success
     * @throws \yii\base\Exception if not found role or permission
     */
    public function rbacAssignmentTeacher($school_id, $teacher_id, $school_teacher_id, $role_name = null)
    {
        // auth RBAC
        $school_package = \common\models\SchoolPackages::findOne(['school_id' => $school_id, 'status' => ACTIVE]);
        if (!$school_package) {
            throw new \yii\base\Exception('not found school package');
        }
        $actor = 'teacher';

        if (!$role_name){
            // get default role
            $package_role = \common\models\PackageRoles::findOne([
                'package_name' => $school_package->package_name, 
                'actor' => $actor, 
                'is_default' => static::IS_DEFAULT,
                'status' => ACTIVE]);
        }else{
            $package_role = \common\models\PackageRoles::findOne([
                'name' => $role_name,
                'actor' => $actor,
                'status' => ACTIVE
            ]);
        }
        if (!$package_role) {
            throw new \yii\base\Exception('not found package role');
        }
        //$package_role = $package_role->name;
        //var_dump($package_role);die;

        $teacher_package_role = new \common\models\TeacherPackageRoles([
            'teacher_id' => $teacher_id,
            'school_id' => $school_id,
            'package_role_name' => $package_role->name,
            'status' => ACTIVE,
        ]);

        if (!$teacher_package_role->save()) {
            throw new \yii\base\Exception('can not save teacher package role');
        }

        // grant app teacher permissions
        $is_granted = false;
        $authManager = Yii::$app->apiTeacherAuthManager;
        if (!$role = $authManager->getRole($package_role->name)) {
            //throw new \yii\base\Exception('not found role ' . $package_role->name);
            // role not found in app, do nothing
        }else{
            $is_granted = true;
            if ($has_assignment = $authManager->getAssignment($package_role->name, $school_teacher_id)) {
                // already assigned
            } else {
                $authManager->assign($role, $school_teacher_id);
            }
        }

        // grant app qrscanner permissions
        $authManager = Yii::$app->apiQrscannerAuthManager;
        if (!$role = $authManager->getRole($package_role->name)) {
            //throw new \yii\base\Exception('not found role ' . $package_role->name);
            // role not found in app, do nothing
        } else {
            $is_granted = true;
            if ($has_assignment = $authManager->getAssignment($package_role->name, $school_teacher_id)) {
                // already assigned
            } else {
                $authManager->assign($role, $school_teacher_id);
            }
        }

        if (!$is_granted){
            // role not found in any application, so throws an exception here
            throw new \yii\base\Exception('not found role ' . $package_role->name);
        }

        // grant admin school permissions
        $school_authManager = Yii::$app->adminSchoolAuthManager;
        if (!$role = $school_authManager->getRole($package_role->name)) {
            //throw new \yii\base\Exception('not found role ' . $package_role->name);
            // role not found in app, do nothing
        }else{
            $is_granted = true;
            if ($has_assignment = $school_authManager->getAssignment($package_role->name, $school_teacher_id)) {
                // already assigned
            } else {
                $school_authManager->assign($role, $school_teacher_id);
            }
        }

        return true;
    }

    /**
     * revoke role from teacher
     * @param int $school_id
     * @param int $teacher_id
     * @param int $school_teacher_id
     * @param string $role_name
     * @return true if assignment success
     * @throws \yii\base\Exception if not found role or permission
     */
    public function rbacRevokeTeacher($school_id,$teacher_id,$school_teacher_id,$role_name=null){
        $school_package = \common\models\SchoolPackages::findOne(['school_id' => $school_id, 'status' => ACTIVE]);
        if (!$school_package) {
            throw new \yii\base\Exception('not found school package');
        }
        $actor = 'teacher';
        if (!$role_name){
            $package_role = \common\models\PackageRoles::findOne([
                                'package_name' => $school_package->package_name, 
                                'actor' => $actor,
                                'is_default' => static::IS_DEFAULT,
                                'status' => ACTIVE]);
        }else{
            $package_role = \common\models\PackageRoles::findOne([
                'name' => $role_name,
                'actor' => $actor,
                'status' => ACTIVE
            ]);
        }
        if (!$package_role) {
            throw new \yii\base\Exception('not found package role');
        }

        \common\models\TeacherPackageRoles::deleteAll([
            'teacher_id' => $teacher_id,
            'school_id' => $school_id,
        ]);

        $authManager = Yii::$app->apiTeacherAuthManager;
        if (!$role = $authManager->getRole($package_role->name)) {
            //throw new \yii\base\Exception('not found role ' . $package_role->name);
        }else{
            if ($has_assignment = $authManager->getAssignment($package_role->name, $school_teacher_id)) {
                $authManager->revoke($role, $school_teacher_id);
            } 
        }

        $authManager = Yii::$app->apiQrscannerAuthManager;
        if (!$role = $authManager->getRole($package_role->name)) {
            //throw new \yii\base\Exception('not found role ' . $package_role->name);
        } else {
            if ($has_assignment = $authManager->getAssignment($package_role->name, $school_teacher_id)) {
                $authManager->revoke($role, $school_teacher_id);
            }
        }

        $school_authManager = Yii::$app->adminSchoolAuthManager;
        if (!$role = $school_authManager->getRole($package_role->name)) {
            //throw new \yii\base\Exception('not found role ' . $package_role->name);
        }else{
            if ($has_assignment = $school_authManager->getAssignment($package_role->name, $school_teacher_id)) {
                $school_authManager->revoke($role, $school_teacher_id);
            } 
        }

        return true;
    }

    /**
     * RBAC assignment
     * grant parent app permission
     * @param int $school_id
     * @param int $school_kid_id
     * @return true if assignment success
     * @throws \yii\base\Exception if not found role or permission
     */
    public function rbacAssignmentParent($school_id, $parent_id, $school_kid_id)
    {
        // auth RBAC
        $school_package = \common\models\SchoolPackages::findOne(['school_id' => $school_id, 'status' => ACTIVE]);
        if (!$school_package) {
            throw new \yii\base\Exception('not found school package');
        }
        $school_kid = SchoolKids::findOne(['id'=>$school_kid_id,'status'=>ACTIVE]);
        if (!$school_kid){
            throw new \yii\base\Exception('not found school kid');
        }

        $actor = 'parent';
        $parent_package_role = \common\models\PackageRoles::findOne([
            'package_name' => $school_package->package_name, 
            'actor' => $actor,
            'is_default' => static::IS_DEFAULT,
            'status' => ACTIVE]);
        if (!$parent_package_role) {
            throw new \yii\base\Exception('not found package role');
        }

        $package_role = $parent_package_role->name;

        
        // we check if parent has already assigned to package role, if YES, then ignore, otherwise, we create new parent package role
        // this case is when one parent has more than one kids in school

        // @deprecated: replace by ParentKidPackageRoles
        // $parent_package_role_item_exist = \common\models\ParentPackageRoles::findOne([
        //     'parent_id' => $parent_id,
        //     'school_id' => $school_id,
        //     'package_role_name' => $package_role
        // ]);

        // if ($parent_package_role_item_exist){
        //     // already exist, ignore
        // }else{
        //     // add parent package role
        //     $parent_package_role_item = new \common\models\ParentPackageRoles([
        //         'parent_id' => $parent_id,
        //         'school_id' => $school_id,
        //         'package_role_name' => $package_role,
        //         'status' => ACTIVE,
        //     ]);
        //     if (!$parent_package_role_item->save()) {
        //         throw new \yii\base\Exception('can not save parent package role');
        //     }
        // }

        $parent_package_role_item_exist = ParentKidPackageRoles::findOne([
            'parent_id' => $parent_id,
            'kid_id' => $school_kid->kid_id,
            'school_id' => $school_id,
            'package_role_name' => $package_role
        ]);

        if ($parent_package_role_item_exist) {
            // already exist, ignore
            $parent_package_role_item_exist->status = ACTIVE;
            $parent_package_role_item_exist->save();
        } else {
            // add parent package role
            $parent_package_role_item = new ParentKidPackageRoles([
                'parent_id' => $parent_id,
                'kid_id' => $school_kid->kid_id,
                'school_id' => $school_id,
                'package_role_name' => $package_role,
                'status' => ACTIVE,
            ]);
            if (!$parent_package_role_item->save()) {
                throw new \yii\base\Exception('can not save parent package role');
            }
        }

        $authManager = Yii::$app->apiParentAuthManager;
        if (!$role = $authManager->getRole($package_role)) {
            throw new \yii\base\Exception('not found role ' . $package_role);
        }

        if ($has_assignment = $authManager->getAssignment($package_role, $school_kid_id)) {
            // already assigned
        } else {
            $authManager->assign($role, $school_kid_id);
        }
        return true;
    }

    /**
     * RBAC revoke parent
     * grant parent app permission
     * @param int $school_id
     * @param int $school_kid_id
     * @return true if assignment success
     * @throws \yii\base\Exception if not found role or permission
     */
    public function rbacRevokeParent($school_id, $parent_id, $school_kid_id, $deleteAll=true)
    {
        // auth RBAC
        $school_package = \common\models\SchoolPackages::findOne(['school_id' => $school_id, 'status' => ACTIVE]);
        if (!$school_package) {
            throw new \yii\base\Exception('not found school package');
        }
        $school_kid = SchoolKids::findOne(['id' => $school_kid_id, 'status' => ACTIVE]);
        if (!$school_kid) {
            throw new \yii\base\Exception('not found school kid');
        }

        $actor = 'parent';
        $parent_package_role = \common\models\PackageRoles::findOne(['package_name' => $school_package->package_name, 'actor' => $actor, 'status' => ACTIVE]);
        if (!$parent_package_role) {
            throw new \yii\base\Exception('not found package role');
        }

        $package_role = $parent_package_role->name;

        if ($deleteAll) {
            ParentKidPackageRoles::deleteAll([
                'parent_id' => $parent_id,
                'kid_id' => $school_kid->kid_id,
                'school_id' => $school_id,
            ]);
        } else {
            //todo
        }

        $authManager = Yii::$app->apiParentAuthManager;
        if (!$role = $authManager->getRole($package_role)) {
            throw new \yii\base\Exception('not found role ' . $package_role);
        }

        if ($has_assignment = $authManager->getAssignment($package_role, $school_kid_id)) {
            $authManager->revoke($role, $school_kid_id);
        }
        return true;
    }
}
