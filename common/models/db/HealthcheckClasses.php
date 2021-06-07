<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "healthcheck_classes".
 *
 * @property int $id
 * @property int $class_id
 * @property int $healthcheck_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Classes $class
 * @property Healthchecks $healthcheck
 */
class HealthcheckClasses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'healthcheck_classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'healthcheck_id'], 'required'],
            [['class_id', 'healthcheck_id', 'status'], 'default', 'value' => null],
            [['class_id', 'healthcheck_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['healthcheck_id'], 'exist', 'skipOnError' => true, 'targetClass' => Healthchecks::className(), 'targetAttribute' => ['healthcheck_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'healthcheck_id' => 'Healthcheck ID',
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
    public function getHealthcheck()
    {
        return $this->hasOne(Healthchecks::className(), ['id' => 'healthcheck_id']);
    }
}
