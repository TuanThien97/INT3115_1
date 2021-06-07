<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "districts".
 *
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class DistrictsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'districts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['city_id', 'status'], 'default', 'value' => null],
            [['city_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => CitiesDB::className(), 'targetAttribute' => ['city_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'city_id' => 'City ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
