<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_prescription_medicine_photos".
 *
 * @property int $id
 * @property int $kid_prescription_medicine_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidPrescriptionMedicines $kidPrescriptionMedicine
 * @property Photos $photo
 */
class KidPrescriptionMedicinePhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_prescription_medicine_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_prescription_medicine_id', 'photo_id'], 'required'],
            [['kid_prescription_medicine_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_prescription_medicine_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_prescription_medicine_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidPrescriptionMedicines::className(), 'targetAttribute' => ['kid_prescription_medicine_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_prescription_medicine_id' => 'Kid Prescription Medicine ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptionMedicine()
    {
        return $this->hasOne(KidPrescriptionMedicines::className(), ['id' => 'kid_prescription_medicine_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
