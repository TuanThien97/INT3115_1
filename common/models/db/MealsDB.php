<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "meals".
 *
 * @property integer $id
 * @property string $name
 * @property string $from_time
 * @property string $to_time
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class MealsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'from_time', 'to_time', 'sequence'], 'required'],
            [['name'], 'string'],
            [['from_time', 'to_time', 'created_at', 'updated_at'], 'safe'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer']
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
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
