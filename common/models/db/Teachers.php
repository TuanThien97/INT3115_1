<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teachers".
 *
 * @property int $id
 * @property string $phone_number
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $address
 * @property int $gender
 * @property string $dob
 * @property int $university_id
 * @property int $photo_id
 * @property string $code
 * @property string $qrcode
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $note
 * @property string $password
 * @property int $is_temporary_password
 * @property string $temp_password
 *
 * @property AlbumComments[] $albumComments
 * @property AlbumLikes[] $albumLikes
 * @property Albums[] $albums
 * @property KidAbsences[] $kidAbsences
 * @property KidCheckins[] $kidCheckins
 * @property KidCheckouts[] $kidCheckouts
 * @property KidDailyEatEvaluations[] $kidDailyEatEvaluations
 * @property KidDailyPoohEvaluations[] $kidDailyPoohEvaluations
 * @property KidDailyReportComments[] $kidDailyReportComments
 * @property KidDailyReportLikes[] $kidDailyReportLikes
 * @property KidDailyReports[] $kidDailyReports
 * @property KidDailySleepEvaluations[] $kidDailySleepEvaluations
 * @property KidMessageComments[] $kidMessageComments
 * @property KidMessageLikes[] $kidMessageLikes
 * @property KidMessages[] $kidMessages
 * @property KidPrescriptions[] $kidPrescriptions
 * @property KidWeeklyReportComments[] $kidWeeklyReportComments
 * @property KidWeeklyReportLikes[] $kidWeeklyReportLikes
 * @property KidWeeklyReports[] $kidWeeklyReports
 * @property PhotoComments[] $photoComments
 * @property PhotoLikes[] $photoLikes
 * @property Photos[] $photos
 * @property SchoolAssetOrders[] $schoolAssetOrders
 * @property SchoolTeachers[] $schoolTeachers
 * @property TeacherAbsences[] $teacherAbsences
 * @property TeacherCheckins[] $teacherCheckins
 * @property TeacherClasses[] $teacherClasses
 * @property TeacherPushNotifications[] $teacherPushNotifications
 * @property TeacherSchoolPrivateCourseClasses[] $teacherSchoolPrivateCourseClasses
 * @property TeacherSettings[] $teacherSettings
 * @property Photos $photo
 * @property Universities $university
 */
class Teachers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teachers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_number', 'first_name', 'last_name'], 'required'],
            [['address', 'qrcode', 'note'], 'string'],
            [['gender', 'university_id', 'photo_id', 'status', 'is_temporary_password'], 'default', 'value' => null],
            [['gender', 'university_id', 'photo_id', 'status', 'is_temporary_password'], 'integer'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['phone_number'], 'string', 'max' => 32],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['email', 'password'], 'string', 'max' => 256],
            [['code'], 'string', 'max' => 16],
            [['temp_password'], 'string', 'max' => 512],
            [['phone_number'], 'unique'],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
            [['university_id'], 'exist', 'skipOnError' => true, 'targetClass' => Universities::className(), 'targetAttribute' => ['university_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone_number' => 'Phone Number',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'address' => 'Address',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'university_id' => 'University ID',
            'photo_id' => 'Photo ID',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'note' => 'Note',
            'password' => 'Password',
            'is_temporary_password' => 'Is Temporary Password',
            'temp_password' => 'Temp Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumComments()
    {
        return $this->hasMany(AlbumComments::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumLikes()
    {
        return $this->hasMany(AlbumLikes::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Albums::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidAbsences()
    {
        return $this->hasMany(KidAbsences::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckins()
    {
        return $this->hasMany(KidCheckins::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckouts()
    {
        return $this->hasMany(KidCheckouts::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyEatEvaluations()
    {
        return $this->hasMany(KidDailyEatEvaluations::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyPoohEvaluations()
    {
        return $this->hasMany(KidDailyPoohEvaluations::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportComments()
    {
        return $this->hasMany(KidDailyReportComments::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportLikes()
    {
        return $this->hasMany(KidDailyReportLikes::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReports()
    {
        return $this->hasMany(KidDailyReports::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailySleepEvaluations()
    {
        return $this->hasMany(KidDailySleepEvaluations::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageComments()
    {
        return $this->hasMany(KidMessageComments::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageLikes()
    {
        return $this->hasMany(KidMessageLikes::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessages()
    {
        return $this->hasMany(KidMessages::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptions()
    {
        return $this->hasMany(KidPrescriptions::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportComments()
    {
        return $this->hasMany(KidWeeklyReportComments::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportLikes()
    {
        return $this->hasMany(KidWeeklyReportLikes::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReports()
    {
        return $this->hasMany(KidWeeklyReports::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoComments()
    {
        return $this->hasMany(PhotoComments::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoLikes()
    {
        return $this->hasMany(PhotoLikes::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolAssetOrders()
    {
        return $this->hasMany(SchoolAssetOrders::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolTeachers()
    {
        return $this->hasMany(SchoolTeachers::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherAbsences()
    {
        return $this->hasMany(TeacherAbsences::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherCheckins()
    {
        return $this->hasMany(TeacherCheckins::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherClasses()
    {
        return $this->hasMany(TeacherClasses::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherPushNotifications()
    {
        return $this->hasMany(TeacherPushNotifications::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherSchoolPrivateCourseClasses()
    {
        return $this->hasMany(TeacherSchoolPrivateCourseClasses::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherSettings()
    {
        return $this->hasMany(TeacherSettings::className(), ['teacher_id' => 'id']);
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
    public function getUniversity()
    {
        return $this->hasOne(Universities::className(), ['id' => 'university_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClasses(){
        return $this->hasMany(Classes::className(),['id' => 'class_id'])
                        ->viaTable('teacher_classes',['teacher_id' => 'id'])
                        ->where(['status' => ACTIVE]);
    }
}
