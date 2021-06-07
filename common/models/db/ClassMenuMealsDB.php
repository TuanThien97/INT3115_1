<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_menu_meals".
 *
 * @property integer $id
 * @property integer $class_menu_id
 * @property integer $meal_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ClassMenuMealsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_menu_meals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_menu_id', 'meal_id'], 'required'],
            [['class_menu_id', 'meal_id', 'status'], 'default', 'value' => null],
            [['class_menu_id', 'meal_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['class_menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClassMenusDB::className(), 'targetAttribute' => ['class_menu_id' => 'id']],
            [['meal_id'], 'exist', 'skipOnError' => true, 'targetClass' => MealsDB::className(), 'targetAttribute' => ['meal_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_menu_id' => 'Class Menu ID',
            'meal_id' => 'Meal ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
