<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_package_roles".
 *
 * @property integer $principal_id
 * @property integer $school_id
 * @property string $package_role_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PrincipalPackageRolesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'principal_package_roles';
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
        return ['principal_id', 'school_id', 'package_role_name'];
    } 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['principal_id', 'school_id', 'package_role_name'], 'required'],
            [['principal_id', 'school_id', 'status'], 'default', 'value' => null],
            [['principal_id', 'school_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['package_role_name'], 'string', 'max' => 64],
            [['principal_id', 'school_id', 'package_role_name'], 'unique', 'targetAttribute' => ['principal_id', 'school_id', 'package_role_name']],
            [['package_role_name'], 'exist', 'skipOnError' => true, 'targetClass' => PackageRoles::className(), 'targetAttribute' => ['package_role_name' => 'name']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'principal_id' => 'Principal ID',
            'school_id' => 'School ID',
            'package_role_name' => 'Package Role Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
