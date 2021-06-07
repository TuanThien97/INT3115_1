<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_menu_meal_foods".
 *
 * @property integer $id
 * @property integer $class_menu_meal_id
 * @property integer $food_id
 * @property integer $sequence
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ClassMenuMealFoodsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_menu_meal_foods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_menu_meal_id', 'food_id', 'sequence'], 'required'],
            [['class_menu_meal_id', 'food_id', 'sequence', 'status'], 'default', 'value' => null],
            [['class_menu_meal_id', 'food_id', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['class_menu_meal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClassMenuMealsDB::className(), 'targetAttribute' => ['class_menu_meal_id' => 'id']],
            [['food_id'], 'exist', 'skipOnError' => true, 'targetClass' => FoodsDB::className(), 'targetAttribute' => ['food_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_menu_meal_id' => 'Class Menu Meal ID',
            'food_id' => 'Food ID',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
