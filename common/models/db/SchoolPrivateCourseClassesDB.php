<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_private_course_classes".
 *
 * @property integer $id
 * @property integer $school_private_course_id
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $finish_date
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolPrivateCourseClassesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_private_course_classes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_private_course_id', 'name', 'start_date', 'finish_date'], 'required'],
            [['school_private_course_id', 'status'], 'default', 'value' => null],
            [['school_private_course_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['start_date', 'finish_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['school_private_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolPrivateCoursesDB::className(), 'targetAttribute' => ['school_private_course_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_private_course_id' => 'School Private Course ID',
            'name' => 'Name',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'finish_date' => 'Finish Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
