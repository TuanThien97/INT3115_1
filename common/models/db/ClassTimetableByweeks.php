<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_timetable_byweeks".
 *
 * @property int $id
 * @property int $class_id
 * @property int $week
 * @property int $year
 * @property string $topic
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassTimetableByweekDetails[] $classTimetableByweekDetails
 * @property Classes $class
 */
class ClassTimetableByweeks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_timetable_byweeks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'week', 'year'], 'required'],
            [['class_id', 'week', 'year', 'status'], 'default', 'value' => null],
            [['class_id', 'week', 'year', 'status'], 'integer'],
            [['topic'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'week' => 'Week',
            'year' => 'Year',
            'topic' => 'Topic',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTimetableByweekDetails()
    {
        return $this->hasMany(ClassTimetableByweekDetails::className(), ['class_timetable_byweek_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }
}
