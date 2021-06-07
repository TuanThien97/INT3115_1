<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "restrict_medicines".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class RestrictMedicinesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restrict_medicines';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'sequence'], 'required'],
            [['description'], 'string'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 256]
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
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
