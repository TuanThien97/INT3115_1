<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "grades".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 */
class GradesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grades';
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
            [['description'], 'string'],
            [['name'], 'string', 'max' => 512]
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
            'description' => 'Description',
        ];
    }
}
