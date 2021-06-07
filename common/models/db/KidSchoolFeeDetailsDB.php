<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_school_fee_details".
 *
 * @property integer $id
 * @property integer $kid_school_fee_id
 * @property integer $fee_type_id
 * @property integer $total
 * @property integer $discount
 * @property string $discount_note
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidSchoolFeeDetailsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_school_fee_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_school_fee_id', 'fee_type_id', 'sequence'], 'required'],
            [['kid_school_fee_id', 'fee_type_id', 'total', 'discount', 'sequence', 'status'], 'default', 'value' => null],
            [['kid_school_fee_id', 'fee_type_id', 'total', 'discount', 'sequence', 'status'], 'integer'],
            [['discount_note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['fee_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FeeTypesDB::className(), 'targetAttribute' => ['fee_type_id' => 'id']],
            [['kid_school_fee_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidSchoolFeeDB::className(), 'targetAttribute' => ['kid_school_fee_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_school_fee_id' => 'Kid School Fee ID',
            'fee_type_id' => 'Fee Type ID',
            'total' => 'Total',
            'discount' => 'Discount',
            'discount_note' => 'Discount Note',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
