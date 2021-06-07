<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teacher_package_roles".
 *
 * @property integer $teacher_id
 * @property integer $school_id
 * @property string $package_role_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class TeacherPackageRolesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_package_roles';
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
        return ['teacher_id', 'school_id', 'package_role_name'];
    } 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'school_id', 'package_role_name'], 'required'],
            [['teacher_id', 'school_id', 'status'], 'default', 'value' => null],
            [['teacher_id', 'school_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['package_role_name'], 'string', 'max' => 64],
            [['teacher_id', 'school_id', 'package_role_name'], 'unique', 'targetAttribute' => ['teacher_id', 'school_id', 'package_role_name']],
            [['package_role_name'], 'exist', 'skipOnError' => true, 'targetClass' => PackageRoles::className(), 'targetAttribute' => ['package_role_name' => 'name']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teacher_id' => 'Teacher ID',
            'school_id' => 'School ID',
            'package_role_name' => 'Package Role Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
