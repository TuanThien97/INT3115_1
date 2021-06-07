<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_grade_fee_discounts".
 *
 * @property integer $id
 * @property integer $school_grade_fee_id
 * @property integer $discount_type_id
 * @property integer $discount_value
 * @property integer $discount_condition_type_id
 * @property integer $discount_condition_operator_id
 * @property integer $discount_condition_value
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolGradeFeeDiscountsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_grade_fee_discounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_grade_fee_id', 'discount_type_id', 'discount_condition_type_id', 'discount_condition_operator_id'], 'required'],
            [['school_grade_fee_id', 'discount_type_id', 'discount_value', 'discount_condition_type_id', 'discount_condition_operator_id', 'discount_condition_value', 'status'], 'default', 'value' => null],
            [['school_grade_fee_id', 'discount_type_id', 'discount_value', 'discount_condition_type_id', 'discount_condition_operator_id', 'discount_condition_value', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['discount_condition_operator_id'], 'exist', 'skipOnError' => true, 'targetClass' => DiscountConditionOperatorsDB::className(), 'targetAttribute' => ['discount_condition_operator_id' => 'id']],
            [['discount_condition_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DiscountConditionTypesDB::className(), 'targetAttribute' => ['discount_condition_type_id' => 'id']],
            [['discount_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DiscountTypesDB::className(), 'targetAttribute' => ['discount_type_id' => 'id']],
            [['school_grade_fee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolGradeFeeDB::className(), 'targetAttribute' => ['school_grade_fee_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_grade_fee_id' => 'School Grade Fee ID',
            'discount_type_id' => 'Discount Type ID',
            'discount_value' => 'Discount Value',
            'discount_condition_type_id' => 'Discount Condition Type ID',
            'discount_condition_operator_id' => 'Discount Condition Operator ID',
            'discount_condition_value' => 'Discount Condition Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
