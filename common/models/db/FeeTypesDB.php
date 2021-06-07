<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "fee_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $unit
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class FeeTypesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'unit'], 'required'],
            [['description'], 'string'],
            [['unit', 'status'], 'default', 'value' => null],
            [['unit', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64]
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
            'description' => 'Description',
            'unit' => 'Unit',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
