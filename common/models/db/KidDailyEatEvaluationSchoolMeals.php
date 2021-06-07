<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_daily_eat_evaluation_school_meals".
 *
 * @property int $id
 * @property int $kid_daily_eat_evaluation_id
 * @property int $school_meal_id
 * @property string $note
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidDailyEatEvaluations $kidDailyEatEvaluation
 * @property SchoolMeals $schoolMeal
 */
class KidDailyEatEvaluationSchoolMeals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_daily_eat_evaluation_school_meals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_daily_eat_evaluation_id', 'school_meal_id'], 'required'],
            [['kid_daily_eat_evaluation_id', 'school_meal_id', 'status'], 'default', 'value' => null],
            [['kid_daily_eat_evaluation_id', 'school_meal_id', 'status'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_daily_eat_evaluation_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidDailyEatEvaluations::className(), 'targetAttribute' => ['kid_daily_eat_evaluation_id' => 'id']],
            [['school_meal_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolMeals::className(), 'targetAttribute' => ['school_meal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_daily_eat_evaluation_id' => 'Kid Daily Eat Evaluation ID',
            'school_meal_id' => 'School Meal ID',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyEatEvaluation()
    {
        return $this->hasOne(KidDailyEatEvaluations::className(), ['id' => 'kid_daily_eat_evaluation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMeal()
    {
        return $this->hasOne(SchoolMeals::className(), ['id' => 'school_meal_id']);
    }
}
