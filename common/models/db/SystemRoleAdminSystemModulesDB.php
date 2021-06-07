<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_role_admin_system_modules".
 *
 * @property string $system_role_name
 * @property string $admin_system_module_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SystemRoleAdminSystemModulesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_role_admin_system_modules';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    // primary key definition 
    public static function primaryKey()
    {
        return ['system_role_name', 'admin_system_module_name'];
    } 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_role_name', 'admin_system_module_name'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_role_name', 'admin_system_module_name'], 'string', 'max' => 64],
            [['system_role_name', 'admin_system_module_name'], 'unique', 'targetAttribute' => ['system_role_name', 'admin_system_module_name']],
            [['admin_system_module_name'], 'exist', 'skipOnError' => true, 'targetClass' => AdminSystemModules::className(), 'targetAttribute' => ['admin_system_module_name' => 'name']],
            [['system_role_name'], 'exist', 'skipOnError' => true, 'targetClass' => SystemRoles::className(), 'targetAttribute' => ['system_role_name' => 'name']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'system_role_name' => 'System Role Name',
            'admin_system_module_name' => 'Admin System Module Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
