<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_school_private_course_registrations".
 *
 * @property integer $id
 * @property integer $kid_id
 * @property integer $school_private_course_id
 * @property string $note
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidSchoolPrivateCourseRegistrationsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_school_private_course_registrations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_id', 'school_private_course_id'], 'required'],
            [['kid_id', 'school_private_course_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'school_private_course_id', 'status'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDB::className(), 'targetAttribute' => ['kid_id' => 'id']],
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
            'kid_id' => 'Kid ID',
            'school_private_course_id' => 'School Private Course ID',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
