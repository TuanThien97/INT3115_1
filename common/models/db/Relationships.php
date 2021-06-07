<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "relationships".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Protectors[] $protectors
 */
class Relationships extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relationships';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 256],
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
            'description' => 'Description',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProtectors()
    {
        return $this->hasMany(Protectors::className(), ['relationship_id' => 'id']);
    }
}
