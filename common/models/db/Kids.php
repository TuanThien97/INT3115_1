<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kids".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $dob
 * @property int $gender
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $note
 *
 * @property KidAbsences[] $kidAbsences
 * @property KidAllergicFoods[] $kidAllergicFoods
 * @property KidCheckins[] $kidCheckins
 * @property KidCheckouts[] $kidCheckouts
 * @property KidClasses[] $kidClasses
 * @property KidDailyReportComments[] $kidDailyReportComments
 * @property KidDailyReportLikes[] $kidDailyReportLikes
 * @property KidDailyReports[] $kidDailyReports
 * @property KidMessageComments[] $kidMessageComments
 * @property KidMessageLikes[] $kidMessageLikes
 * @property KidMessages[] $kidMessages
 * @property KidMonthlyFees[] $kidMonthlyFees
 * @property KidPrescriptions[] $kidPrescriptions
 * @property KidSchoolGradeFeeDiscounts[] $kidSchoolGradeFeeDiscounts
 * @property KidSchoolPrivateCourseClasses[] $kidSchoolPrivateCourseClasses
 * @property KidSchoolPrivateCourses[] $kidSchoolPrivateCourses
 * @property KidSchoolServices[] $kidSchoolServices
 * @property KidWeeklyReportComments[] $kidWeeklyReportComments
 * @property KidWeeklyReportLikes[] $kidWeeklyReportLikes
 * @property KidWeeklyReports[] $kidWeeklyReports
 * @property Photos $photo
 * @property ParentKids[] $parentKids
 * @property PhotoComments[] $photoComments
 * @property PhotoKids[] $photoKids
 * @property PhotoLikes[] $photoLikes
 * @property Photos[] $photos
 * @property Protectors[] $protectors
 * @property SchoolKids[] $schoolKids
 */
class Kids extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'dob', 'gender'], 'required'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['gender', 'photo_id', 'status'], 'default', 'value' => null],
            [['gender', 'photo_id', 'status'], 'integer'],
            [['note'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 256],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'note' => 'Note',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidAbsences()
    {
        return $this->hasMany(KidAbsences::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidAllergicFoods()
    {
        return $this->hasMany(KidAllergicFoods::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckins()
    {
        return $this->hasMany(KidCheckins::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckouts()
    {
        return $this->hasMany(KidCheckouts::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidClasses()
    {
        return $this->hasMany(KidClasses::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportComments()
    {
        return $this->hasMany(KidDailyReportComments::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportLikes()
    {
        return $this->hasMany(KidDailyReportLikes::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReports()
    {
        return $this->hasMany(KidDailyReports::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageComments()
    {
        return $this->hasMany(KidMessageComments::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageLikes()
    {
        return $this->hasMany(KidMessageLikes::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessages()
    {
        return $this->hasMany(KidMessages::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMonthlyFees()
    {
        return $this->hasMany(KidMonthlyFees::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptions()
    {
        return $this->hasMany(KidPrescriptions::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidSchoolGradeFeeDiscounts()
    {
        return $this->hasMany(KidSchoolGradeFeeDiscounts::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidSchoolPrivateCourseClasses()
    {
        return $this->hasMany(KidSchoolPrivateCourseClasses::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidSchoolPrivateCourses()
    {
        return $this->hasMany(KidSchoolPrivateCourses::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidSchoolServices()
    {
        return $this->hasMany(KidSchoolServices::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportComments()
    {
        return $this->hasMany(KidWeeklyReportComments::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportLikes()
    {
        return $this->hasMany(KidWeeklyReportLikes::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReports()
    {
        return $this->hasMany(KidWeeklyReports::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentKids()
    {
        return $this->hasMany(ParentKids::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoComments()
    {
        return $this->hasMany(PhotoComments::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoKids()
    {
        return $this->hasMany(PhotoKids::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoLikes()
    {
        return $this->hasMany(PhotoLikes::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProtectors()
    {
        return $this->hasMany(Protectors::className(), ['kid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolKids()
    {
        return $this->hasMany(SchoolKids::className(), ['kid_id' => 'id']);
    }
}
