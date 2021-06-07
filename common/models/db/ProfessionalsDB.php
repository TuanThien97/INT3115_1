<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "professionals".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ProfessionalsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'professionals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sequence'], 'required'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['name'], 'unique']
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
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
