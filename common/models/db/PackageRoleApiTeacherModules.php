<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "package_role_api_teacher_modules".
 *
 * @property string $package_role_name
 * @property string $api_teacher_module_name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApiTeacherModules $apiTeacherModuleName
 * @property PackageRoles $packageRoleName
 */
class PackageRoleApiTeacherModules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_role_api_teacher_modules';
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
            [['package_role_name', 'api_teacher_module_name'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['package_role_name', 'api_teacher_module_name'], 'string', 'max' => 64],
            [['package_role_name', 'api_teacher_module_name'], 'unique', 'targetAttribute' => ['package_role_name', 'api_teacher_module_name']],
            [['api_teacher_module_name'], 'exist', 'skipOnError' => true, 'targetClass' => ApiTeacherModules::className(), 'targetAttribute' => ['api_teacher_module_name' => 'name']],
            [['package_role_name'], 'exist', 'skipOnError' => true, 'targetClass' => PackageRoles::className(), 'targetAttribute' => ['package_role_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'package_role_name' => 'Package Role Name',
            'api_teacher_module_name' => 'Api Teacher Module Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiTeacherModuleName()
    {
        return $this->hasOne(ApiTeacherModules::className(), ['name' => 'api_teacher_module_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleName()
    {
        return $this->hasOne(PackageRoles::className(), ['name' => 'package_role_name']);
    }
}
