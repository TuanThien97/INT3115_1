<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_private_courses".
 *
 * @property integer $id
 * @property integer $school_id
 * @property string $name
 * @property string $description
 * @property integer $tuition_fee
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolPrivateCoursesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_private_courses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'name', 'tuition_fee'], 'required'],
            [['school_id', 'tuition_fee', 'status'], 'default', 'value' => null],
            [['school_id', 'tuition_fee', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolsDB::className(), 'targetAttribute' => ['school_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'name' => 'Name',
            'description' => 'Description',
            'tuition_fee' => 'Tuition Fee',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
