<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_school_grade_fee_discounts".
 *
 * @property integer $id
 * @property integer $kid_id
 * @property integer $school_grade_fee_discount_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidSchoolGradeFeeDiscountsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_school_grade_fee_discounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_id', 'school_grade_fee_discount_id'], 'required'],
            [['kid_id', 'school_grade_fee_discount_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'school_grade_fee_discount_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDB::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['school_grade_fee_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolGradeFeeDiscountsDB::className(), 'targetAttribute' => ['school_grade_fee_discount_id' => 'id']]
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
            'school_grade_fee_discount_id' => 'School Grade Fee Discount ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
