<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_timetable_details".
 *
 * @property integer $id
 * @property integer $class_timetable_id
 * @property integer $activity_id
 * @property string $from_time
 * @property string $to_time
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $icon_id
 */
class ClassTimetableDetailsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_timetable_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_timetable_id', 'activity_id', 'from_time', 'to_time', 'sequence'], 'required'],
            [['class_timetable_id', 'activity_id', 'sequence', 'status'], 'default', 'value' => null],
            [['class_timetable_id', 'activity_id', 'sequence', 'status'], 'integer'],
            [['from_time', 'to_time', 'created_at', 'updated_at'], 'safe'],
            [['icon_id'], 'string', 'max' => 16],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => ActivitiesDB::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['class_timetable_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClassTimetablesDB::className(), 'targetAttribute' => ['class_timetable_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_timetable_id' => 'Class Timetable ID',
            'activity_id' => 'Activity ID',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'icon_id' => 'Icon ID',
        ];
    }
}
