<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "growth_evaluates".
 *
 * @property int $id
 * @property int $class_id
 * @property int $school_year_id
 * @property int $field
 * @property string $age
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrowthEvaluateIntents[] $growthEvaluateIntents
 * @property Classes $class
 * @property SchoolYears $schoolYear
 */
class GrowthEvaluates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'growth_evaluates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'school_year_id'], 'required'],
            [['class_id', 'school_year_id', 'field', 'status'], 'default', 'value' => null],
            [['class_id', 'school_year_id', 'field', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['age'], 'string', 'max' => 255],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['school_year_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolYears::className(), 'targetAttribute' => ['school_year_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'school_year_id' => 'School Year ID',
            'field' => 'Field',
            'age' => 'Age',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrowthEvaluateIntents()
    {
        return $this->hasMany(GrowthEvaluateIntents::className(), ['growth_evaluate_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolYear()
    {
        return $this->hasOne(SchoolYears::className(), ['id' => 'school_year_id']);
    }
}
