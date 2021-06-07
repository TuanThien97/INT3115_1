<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_grade_fee".
 *
 * @property integer $id
 * @property integer $school_grade_id
 * @property integer $fee_type_id
 * @property integer $total
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolGradeFeeDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_grade_fee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_grade_id', 'fee_type_id', 'total', 'sequence'], 'required'],
            [['school_grade_id', 'fee_type_id', 'total', 'sequence', 'status'], 'default', 'value' => null],
            [['school_grade_id', 'fee_type_id', 'total', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fee_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FeeTypesDB::className(), 'targetAttribute' => ['fee_type_id' => 'id']],
            [['school_grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolGradesDB::className(), 'targetAttribute' => ['school_grade_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_grade_id' => 'School Grade ID',
            'fee_type_id' => 'Fee Type ID',
            'total' => 'Total',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
