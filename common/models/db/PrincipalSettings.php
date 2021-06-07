<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_settings".
 *
 * @property int $id
 * @property int $principal_id
 * @property string $option
 * @property string $value
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Principals $principal
 */
class PrincipalSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'principal_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['principal_id', 'option', 'value'], 'required'],
            [['principal_id', 'status'], 'default', 'value' => null],
            [['principal_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['option', 'value'], 'string', 'max' => 64],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Principals::className(), 'targetAttribute' => ['principal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'principal_id' => 'Principal ID',
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
    public function getPrincipal()
    {
        return $this->hasOne(Principals::className(), ['id' => 'principal_id']);
    }
}
