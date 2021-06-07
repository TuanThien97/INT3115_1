<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "healthchecks".
 *
 * @property int $id
 * @property int $school_id
 * @property string $title
 * @property string $note
 * @property string $day
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property HealthcheckClasses[] $healthcheckClasses
 * @property Schools $school
 * @property KidHealthchecks[] $kidHealthchecks
 */
class Healthchecks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'healthchecks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'title'], 'required'],
            [['school_id', 'status'], 'default', 'value' => null],
            [['school_id', 'status'], 'integer'],
            [['note'], 'string'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schools::className(), 'targetAttribute' => ['school_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'title' => 'Title',
            'note' => 'Note',
            'day' => 'Day',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHealthcheckClasses()
    {
        return $this->hasMany(HealthcheckClasses::className(), ['healthcheck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidHealthchecks()
    {
        return $this->hasMany(KidHealthchecks::className(), ['healthcheck_id' => 'id']);
    }
}
