<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_packages".
 *
 * @property integer $school_id
 * @property string $package_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolPackagesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_packages';
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
        return ['school_id', 'package_name'];
    } 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'package_name'], 'required'],
            [['school_id', 'status'], 'default', 'value' => null],
            [['school_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['package_name'], 'string', 'max' => 64],
            [['school_id', 'package_name'], 'unique', 'targetAttribute' => ['school_id', 'package_name']],
            [['package_name'], 'exist', 'skipOnError' => true, 'targetClass' => Packages::className(), 'targetAttribute' => ['package_name' => 'name']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school_id' => 'School ID',
            'package_name' => 'Package Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
