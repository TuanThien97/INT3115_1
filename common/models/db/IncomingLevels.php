<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "incoming_levels".
 *
 * @property int $id
 * @property string $name
 * @property int $from_val
 * @property int $to_val
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Parents[] $parents
 */
class IncomingLevels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incoming_levels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'from_val', 'to_val', 'sequence'], 'required'],
            [['from_val', 'to_val', 'sequence', 'status'], 'default', 'value' => null],
            [['from_val', 'to_val', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'from_val' => 'From Val',
            'to_val' => 'To Val',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Parents::className(), ['incoming_level_id' => 'id']);
    }
}
