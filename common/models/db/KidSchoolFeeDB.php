<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_school_fee".
 *
 * @property integer $id
 * @property integer $kid_id
 * @property integer $month
 * @property integer $year
 * @property string $note
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidSchoolFeeDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_school_fee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_id', 'month', 'year'], 'required'],
            [['kid_id', 'month', 'year', 'status'], 'default', 'value' => null],
            [['kid_id', 'month', 'year', 'status'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDB::className(), 'targetAttribute' => ['kid_id' => 'id']]
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
            'month' => 'Month',
            'year' => 'Year',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
