<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_food_photos".
 *
 * @property int $id
 * @property int $school_food_id
 * @property string $photo_url
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolFoods $schoolFood
 */
class SchoolFoodPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_food_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_food_id'], 'required'],
            [['school_food_id', 'status'], 'default', 'value' => null],
            [['school_food_id', 'status'], 'integer'],
            [['photo_url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['school_food_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolFoods::className(), 'targetAttribute' => ['school_food_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_food_id' => 'School Food ID',
            'photo_url' => 'Photo Url',
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
}
