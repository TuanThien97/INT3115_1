<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_timetables".
 *
 * @property int $id
 * @property int $class_id
 * @property int $dow
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassTimetableDetails[] $classTimetableDetails
 * @property Classes $class
 */
class ClassTimetables extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_timetables';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'dow'], 'required'],
            [['class_id', 'dow', 'status'], 'default', 'value' => null],
            [['class_id', 'dow', 'status'], 'integer'],
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
            'dow' => 'Dow',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTimetableDetails()
    {
        return $this->hasMany(ClassTimetableDetails::className(), ['class_timetable_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }
}
