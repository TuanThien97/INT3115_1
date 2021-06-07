<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "classes".
 *
 * @property int $id
 * @property string $name
 * @property int $school_grade_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassMenus[] $classMenuses
 * @property ClassTimetables[] $classTimetables
 * @property SchoolGrades $schoolGrade
 * @property KidAbsences[] $kidAbsences
 * @property KidCheckins[] $kidCheckins
 * @property KidCheckouts[] $kidCheckouts
 * @property KidClasses[] $kidClasses
 * @property KidDailyReports[] $kidDailyReports
 * @property KidPrescriptions[] $kidPrescriptions
 * @property KidWeeklyReports[] $kidWeeklyReports
 * @property TeacherClasses[] $teacherClasses
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['school_grade_id', 'status'], 'default', 'value' => null],
            [['school_grade_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 256],
            [['school_grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolGrades::className(), 'targetAttribute' => ['school_grade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'school_grade_id' => 'School Grade ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassMenuses()
    {
        return $this->hasMany(ClassMenus::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTimetables()
    {
        return $this->hasMany(ClassTimetables::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolGrade()
    {
        return $this->hasOne(SchoolGrades::className(), ['id' => 'school_grade_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidAbsences()
    {
        return $this->hasMany(KidAbsences::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckins()
    {
        return $this->hasMany(KidCheckins::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckouts()
    {
        return $this->hasMany(KidCheckouts::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidClasses()
    {
        return $this->hasMany(KidClasses::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReports()
    {
        return $this->hasMany(KidDailyReports::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptions()
    {
        return $this->hasMany(KidPrescriptions::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReports()
    {
        return $this->hasMany(KidWeeklyReports::className(), ['class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherClasses()
    {
        return $this->hasMany(TeacherClasses::className(), ['class_id' => 'id']);
    }
}
