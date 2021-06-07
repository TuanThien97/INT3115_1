<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class SchoolMenus extends \common\models\db\SchoolMenus
{
	// add timestamp behavior
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
				'value' => new Expression('NOW()'),
			],
		];
	}
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSchoolMenuMeals()
	{
		return $this->hasMany(SchoolMenuMeals::className(), ['school_menu_id' => 'id'])
			->leftJoin('school_meals', 'school_meals.id=school_menu_meals.school_meal_id')
			->orderBy(['school_meals.from_time' => SORT_DESC]);
		// return SchoolMenuMeals::find()->joinWith('schoolMeal')->where(['school_menu_id'=>$this->id])->orderBy('school_meals.from_time desc');

	}
}
