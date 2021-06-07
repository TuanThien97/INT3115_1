<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_healthchecks".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $healthcheck_id
 * @property string $day
 * @property double $weight
 * @property double $height
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Classes $class
 * @property Healthchecks $healthcheck
 * @property Kids $kid
 */
class KidHealthchecks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_healthchecks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'healthcheck_id'], 'required'],
            [['kid_id', 'class_id', 'healthcheck_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'healthcheck_id', 'status'], 'integer'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['weight', 'height'], 'number'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['healthcheck_id'], 'exist', 'skipOnError' => true, 'targetClass' => Healthchecks::className(), 'targetAttribute' => ['healthcheck_id' => 'id']],
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
            'healthcheck_id' => 'Healthcheck ID',
            'day' => 'Day',
            'weight' => 'Weight',
            'height' => 'Height',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }
}
