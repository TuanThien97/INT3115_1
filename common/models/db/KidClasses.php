<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_classes".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property string $entered_at
 * @property string $exited_at
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Classes $class
 * @property Kids $kid
 */
class KidClasses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id'], 'required'],
            [['kid_id', 'class_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'status'], 'integer'],
            [['entered_at', 'exited_at', 'created_at', 'updated_at'], 'safe'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_id' => 'Kid ID',
            'class_id' => 'Class ID',
            'entered_at' => 'Entered At',
            'exited_at' => 'Exited At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }
}
