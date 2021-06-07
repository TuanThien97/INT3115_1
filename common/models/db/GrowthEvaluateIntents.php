<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "growth_evaluate_intents".
 *
 * @property int $id
 * @property int $growth_evaluate_id
 * @property int $kid_id
 * @property int $intent_number
 * @property int $value
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrowthEvaluates $growthEvaluate
 * @property Kids $kid
 */
class GrowthEvaluateIntents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'growth_evaluate_intents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['growth_evaluate_id', 'kid_id', 'intent_number'], 'required'],
            [['growth_evaluate_id', 'kid_id', 'intent_number', 'value', 'status'], 'default', 'value' => null],
            [['growth_evaluate_id', 'kid_id', 'intent_number', 'value', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['growth_evaluate_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrowthEvaluates::className(), 'targetAttribute' => ['growth_evaluate_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'growth_evaluate_id' => 'Growth Evaluate ID',
            'kid_id' => 'Kid ID',
            'intent_number' => 'Intent Number',
            'value' => 'Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrowthEvaluate()
    {
        return $this->hasOne(GrowthEvaluates::className(), ['id' => 'growth_evaluate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }
}
