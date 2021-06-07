<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "parent_settings".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $option
 * @property string $value
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Parents $parent
 */
class ParentSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parent_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'option', 'value'], 'required'],
            [['parent_id', 'status'], 'default', 'value' => null],
            [['parent_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['option', 'value'], 'string', 'max' => 64],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parents::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'option' => 'Option',
            'value' => 'Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Parents::className(), ['id' => 'parent_id']);
    }
}
