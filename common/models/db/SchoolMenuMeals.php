<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_menu_meals".
 *
 * @property int $id
 * @property int $school_menu_id
 * @property int $school_meal_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolMenuMealFoods[] $schoolMenuMealFoods
 * @property SchoolMeals $schoolMeal
 * @property SchoolMenus $schoolMenu
 */
class SchoolMenuMeals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_menu_meals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_menu_id', 'school_meal_id'], 'required'],
            [['school_menu_id', 'school_meal_id', 'status'], 'default', 'value' => null],
            [['school_menu_id', 'school_meal_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['school_meal_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolMeals::className(), 'targetAttribute' => ['school_meal_id' => 'id']],
            [['school_menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolMenus::className(), 'targetAttribute' => ['school_menu_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_menu_id' => 'School Menu ID',
            'school_meal_id' => 'School Meal ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMenuMealFoods()
    {
        return $this->hasMany(SchoolMenuMealFoods::className(), ['school_menu_meal_id' => 'id'])->orderBy('school_menu_meal_foods.sequence asc');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMeal()
    {
        return $this->hasOne(SchoolMeals::className(), ['id' => 'school_meal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMenu()
    {
        return $this->hasOne(SchoolMenus::className(), ['id' => 'school_menu_id']);
    }
}
