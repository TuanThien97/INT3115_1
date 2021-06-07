<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_absences".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $teacher_id
 * @property int $absence_reason_id
 * @property string $content
 * @property string $from_date
 * @property string $to_date
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AbsenceReasons $absenceReason
 * @property Classes $class
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidAbsences extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_absences';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'absence_reason_id', 'content', 'from_date', 'to_date'], 'required'],
            [['kid_id', 'class_id', 'teacher_id', 'absence_reason_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'teacher_id', 'absence_reason_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
            [['absence_reason_id'], 'exist', 'skipOnError' => true, 'targetClass' => AbsenceReasons::className(), 'targetAttribute' => ['absence_reason_id' => 'id']],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_id' => 'Kid ID',
            'class_id' => 'Class ID',
            'teacher_id' => 'Teacher ID',
            'absence_reason_id' => 'Absence Reason ID',
            'content' => 'Content',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsenceReason()
    {
        return $this->hasOne(AbsenceReasons::className(), ['id' => 'absence_reason_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
