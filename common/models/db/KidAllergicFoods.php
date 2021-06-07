<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_allergic_foods".
 *
 * @property int $id
 * @property int $kid_id
 * @property string $food
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Kids $kid
 */
class KidAllergicFoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_allergic_foods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'food'], 'required'],
            [['kid_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['food'], 'string', 'max' => 512],
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
            'kid_id' => 'Kid ID',
            'food' => 'Food',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }
}
