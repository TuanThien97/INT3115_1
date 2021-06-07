<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_checkouts".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $protector_id
 * @property int $teacher_id
 * @property string $day
 * @property string $note
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_qrcode
 *
 * @property KidCheckoutPhotos[] $kidCheckoutPhotos
 * @property Classes $class
 * @property Kids $kid
 * @property Protectors $protector
 * @property Teachers $teacher
 */
class KidCheckouts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_checkouts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'teacher_id', 'day'], 'required'],
            [['kid_id', 'class_id', 'protector_id', 'teacher_id', 'status', 'is_qrcode'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'protector_id', 'teacher_id', 'status', 'is_qrcode'], 'integer'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['note'], 'string'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['protector_id'], 'exist', 'skipOnError' => true, 'targetClass' => Protectors::className(), 'targetAttribute' => ['protector_id' => 'id']],
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
            'protector_id' => 'Protector ID',
            'teacher_id' => 'Teacher ID',
            'day' => 'Day',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_qrcode' => 'Is Qrcode',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckoutPhotos()
    {
        return $this->hasMany(KidCheckoutPhotos::className(), ['kid_checkout_id' => 'id']);
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
    public function getProtector()
    {
        return $this->hasOne(Protectors::className(), ['id' => 'protector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
