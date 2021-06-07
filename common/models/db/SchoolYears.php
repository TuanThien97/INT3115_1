<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_years".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Evaluates[] $evaluates
 * @property GrowthEvaluates[] $growthEvaluates
 * @property GrowthReports[] $growthReports
 * @property Points[] $points
 * @property Schools $school
 */
class SchoolYears extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_years';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'start_time', 'end_time'], 'required'],
            [['school_id', 'status'], 'default', 'value' => null],
            [['school_id', 'status'], 'integer'],
            [['start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 256],
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
            'name' => 'Name',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluates()
    {
        return $this->hasMany(Evaluates::className(), ['school_year_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrowthEvaluates()
    {
        return $this->hasMany(GrowthEvaluates::className(), ['school_year_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrowthReports()
    {
        return $this->hasMany(GrowthReports::className(), ['school_year_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Points::className(), ['school_year_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }
}
