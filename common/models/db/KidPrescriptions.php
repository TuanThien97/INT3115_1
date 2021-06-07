<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_prescriptions".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $teacher_id
 * @property string $disease_name
 * @property string $note
 * @property string $from_date
 * @property string $to_date
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $reason_reject
 *
 * @property KidPrescriptionMedicines[] $kidPrescriptionMedicines
 * @property KidPrescriptionPhotos[] $kidPrescriptionPhotos
 * @property Classes $class
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidPrescriptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_prescriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'disease_name', 'from_date', 'to_date'], 'required'],
            [['kid_id', 'class_id', 'teacher_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'teacher_id', 'status'], 'integer'],
            [['disease_name', 'note', 'reason_reject'], 'string'],
            [['from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
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
            'disease_name' => 'Disease Name',
            'note' => 'Note',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'reason_reject' => 'Reason Reject',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptionMedicines()
    {
        return $this->hasMany(KidPrescriptionMedicines::className(), ['kid_prescription_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptionPhotos()
    {
        return $this->hasMany(KidPrescriptionPhotos::className(), ['kid_prescription_id' => 'id']);
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
