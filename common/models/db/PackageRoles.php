<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "package_roles".
 *
 * @property string $name
 * @property string $package_name
 * @property string $actor
 * @property string $description
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_default
 *
 * @property PackageRoleAdminSchoolModules[] $packageRoleAdminSchoolModules
 * @property AdminSchoolModules[] $adminSchoolModuleNames
 * @property PackageRoleAdminTeacherModules[] $packageRoleAdminTeacherModules
 * @property AdminTeacherModules[] $adminTeacherModuleNames
 * @property PackageRoleApiParentModules[] $packageRoleApiParentModules
 * @property ApiParentModules[] $apiParentModuleNames
 * @property PackageRoleApiPrincipalModules[] $packageRoleApiPrincipalModules
 * @property ApiPrincipalModules[] $apiPrincipalModuleNames
 * @property PackageRoleApiQrscannerModules[] $packageRoleApiQrscannerModules
 * @property ApiQrscannerModules[] $apiQrscannerModuleNames
 * @property PackageRoleApiTeacherModules[] $packageRoleApiTeacherModules
 * @property ApiTeacherModules[] $apiTeacherModuleNames
 * @property Packages $packageName
 * @property ParentKidPackageRoles[] $parentKidPackageRoles
 * @property ParentPackageRoles[] $parentPackageRoles
 * @property PrincipalPackageRoles[] $principalPackageRoles
 * @property TeacherPackageRoles[] $teacherPackageRoles
 */
class PackageRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_roles';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'package_name', 'actor'], 'required'],
            [['description'], 'string'],
            [['sequence', 'status', 'is_default'], 'default', 'value' => null],
            [['sequence', 'status', 'is_default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'package_name'], 'string', 'max' => 64],
            [['actor'], 'string', 'max' => 16],
            [['name'], 'unique'],
            [['package_name'], 'exist', 'skipOnError' => true, 'targetClass' => Packages::className(), 'targetAttribute' => ['package_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'package_name' => 'Package Name',
            'actor' => 'Actor',
            'description' => 'Description',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_default' => 'Is Default',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleAdminSchoolModules()
    {
        return $this->hasMany(PackageRoleAdminSchoolModules::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminSchoolModuleNames()
    {
        return $this->hasMany(AdminSchoolModules::className(), ['name' => 'admin_school_module_name'])->viaTable('package_role_admin_school_modules', ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleAdminTeacherModules()
    {
        return $this->hasMany(PackageRoleAdminTeacherModules::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminTeacherModuleNames()
    {
        return $this->hasMany(AdminTeacherModules::className(), ['name' => 'admin_teacher_module_name'])->viaTable('package_role_admin_teacher_modules', ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleApiParentModules()
    {
        return $this->hasMany(PackageRoleApiParentModules::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiParentModuleNames()
    {
        return $this->hasMany(ApiParentModules::className(), ['name' => 'api_parent_module_name'])->viaTable('package_role_api_parent_modules', ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleApiPrincipalModules()
    {
        return $this->hasMany(PackageRoleApiPrincipalModules::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiPrincipalModuleNames()
    {
        return $this->hasMany(ApiPrincipalModules::className(), ['name' => 'api_principal_module_name'])->viaTable('package_role_api_principal_modules', ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleApiQrscannerModules()
    {
        return $this->hasMany(PackageRoleApiQrscannerModules::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiQrscannerModuleNames()
    {
        return $this->hasMany(ApiQrscannerModules::className(), ['name' => 'api_qrscanner_module_name'])->viaTable('package_role_api_qrscanner_modules', ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleApiTeacherModules()
    {
        return $this->hasMany(PackageRoleApiTeacherModules::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiTeacherModuleNames()
    {
        return $this->hasMany(ApiTeacherModules::className(), ['name' => 'api_teacher_module_name'])->viaTable('package_role_api_teacher_modules', ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageName()
    {
        return $this->hasOne(Packages::className(), ['name' => 'package_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentKidPackageRoles()
    {
        return $this->hasMany(ParentKidPackageRoles::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentPackageRoles()
    {
        return $this->hasMany(ParentPackageRoles::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipalPackageRoles()
    {
        return $this->hasMany(PrincipalPackageRoles::className(), ['package_role_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherPackageRoles()
    {
        return $this->hasMany(TeacherPackageRoles::className(), ['package_role_name' => 'name']);
    }
}
