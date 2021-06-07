<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_meals".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $description
 * @property string $from_time
 * @property string $to_time
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Schools $school
 * @property SchoolMenuMeals[] $schoolMenuMeals
 */
class SchoolMeals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_meals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'name', 'from_time', 'to_time', 'sequence'], 'required'],
            [['school_id', 'sequence', 'status'], 'default', 'value' => null],
            [['school_id', 'sequence', 'status'], 'integer'],
            [['description'], 'string'],
            [['from_time', 'to_time', 'created_at', 'updated_at'], 'safe'],
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
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getSchoolMenuMeals()
    {
        return $this->hasMany(SchoolMenuMeals::className(), ['school_meal_id' => 'id']);
    }
}
