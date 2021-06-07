<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_foods".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolFoodPhotos[] $schoolFoodPhotos
 * @property Schools $school
 * @property SchoolMenuMealFoods[] $schoolMenuMealFoods
 */
class SchoolFoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_foods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'name'], 'required'],
            [['school_id', 'status'], 'default', 'value' => null],
            [['school_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolFoodPhotos()
    {
        return $this->hasMany(SchoolFoodPhotos::className(), ['school_food_id' => 'id']);
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
    public function getSchoolMenuMealFoods()
    {
        return $this->hasMany(SchoolMenuMealFoods::className(), ['school_food_id' => 'id']);
    }
}
