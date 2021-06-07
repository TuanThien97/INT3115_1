<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "food_photos".
 *
 * @property integer $id
 * @property integer $food_id
 * @property string $photo_url
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class FoodPhotosDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'food_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['food_id'], 'required'],
            [['food_id', 'status'], 'default', 'value' => null],
            [['food_id', 'status'], 'integer'],
            [['photo_url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['food_id'], 'exist', 'skipOnError' => true, 'targetClass' => FoodsDB::className(), 'targetAttribute' => ['food_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'food_id' => 'Food ID',
            'photo_url' => 'Photo Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
