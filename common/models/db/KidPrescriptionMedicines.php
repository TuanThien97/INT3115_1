<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_prescription_medicines".
 *
 * @property int $id
 * @property int $kid_prescription_id
 * @property string $medicine_name
 * @property int $medicine_unit_id
 * @property string $usage
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidPrescriptionMedicinePhotos[] $kidPrescriptionMedicinePhotos
 * @property KidPrescriptions $kidPrescription
 * @property MedicineUnits $medicineUnit
 */
class KidPrescriptionMedicines extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_prescription_medicines';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_prescription_id', 'medicine_name'], 'required'],
            [['kid_prescription_id', 'medicine_unit_id', 'status'], 'default', 'value' => null],
            [['kid_prescription_id', 'medicine_unit_id', 'status'], 'integer'],
            [['medicine_name', 'usage'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_prescription_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidPrescriptions::className(), 'targetAttribute' => ['kid_prescription_id' => 'id']],
            [['medicine_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => MedicineUnits::className(), 'targetAttribute' => ['medicine_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_prescription_id' => 'Kid Prescription ID',
            'medicine_name' => 'Medicine Name',
            'medicine_unit_id' => 'Medicine Unit ID',
            'usage' => 'Usage',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptionMedicinePhotos()
    {
        return $this->hasMany(KidPrescriptionMedicinePhotos::className(), ['kid_prescription_medicine_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescription()
    {
        return $this->hasOne(KidPrescriptions::className(), ['id' => 'kid_prescription_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicineUnit()
    {
        return $this->hasOne(MedicineUnits::className(), ['id' => 'medicine_unit_id']);
    }
}
