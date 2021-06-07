<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_menu_meal_foods".
 *
 * @property int $id
 * @property int $school_menu_meal_id
 * @property int $school_food_id
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolFoods $schoolFood
 * @property SchoolMenuMeals $schoolMenuMeal
 */
class SchoolMenuMealFoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_menu_meal_foods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_menu_meal_id', 'school_food_id', 'sequence'], 'required'],
            [['school_menu_meal_id', 'school_food_id', 'sequence', 'status'], 'default', 'value' => null],
            [['school_menu_meal_id', 'school_food_id', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['school_food_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolFoods::className(), 'targetAttribute' => ['school_food_id' => 'id']],
            [['school_menu_meal_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolMenuMeals::className(), 'targetAttribute' => ['school_menu_meal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_menu_meal_id' => 'School Menu Meal ID',
            'school_food_id' => 'School Food ID',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolFood()
    {
        return $this->hasOne(SchoolFoods::className(), ['id' => 'school_food_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMenuMeal()
    {
        return $this->hasOne(SchoolMenuMeals::className(), ['id' => 'school_menu_meal_id']);
    }
}
