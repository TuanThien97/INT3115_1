<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "photos".
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $class_id
 * @property int $kid_id
 * @property int $principal_id
 * @property string $title
 * @property string $description
 * @property string $bucket
 * @property string $filepath
 * @property string $filename
 * @property int $is_trivial
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AlbumPhotos[] $albumPhotos
 * @property KidCheckinPhotos[] $kidCheckinPhotos
 * @property KidCheckoutPhotos[] $kidCheckoutPhotos
 * @property KidDailyReportCommentPhotos[] $kidDailyReportCommentPhotos
 * @property KidDailyReportPhotos[] $kidDailyReportPhotos
 * @property KidMessageCommentPhotos[] $kidMessageCommentPhotos
 * @property KidMessagePhotos[] $kidMessagePhotos
 * @property KidPrescriptionMedicinePhotos[] $kidPrescriptionMedicinePhotos
 * @property KidWeeklyReportCommentPhotos[] $kidWeeklyReportCommentPhotos
 * @property KidWeeklyReportPhotos[] $kidWeeklyReportPhotos
 * @property PhotoCommentPhotos[] $photoCommentPhotos
 * @property PhotoComments[] $photoComments
 * @property PhotoKids[] $photoKids
 * @property PhotoLikes[] $photoLikes
 * @property Classes $class
 * @property Kids $kid
 * @property Principals $principal
 * @property Teachers $teacher
 * @property Protectors[] $protectors
 */
class Photos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'class_id', 'kid_id', 'principal_id', 'is_trivial', 'status'], 'default', 'value' => null],
            [['teacher_id', 'class_id', 'kid_id', 'principal_id', 'is_trivial', 'status'], 'integer'],
            [['title', 'description'], 'string'],
            [['bucket', 'filepath', 'filename'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['bucket', 'filename'], 'string', 'max' => 256],
            [['filepath'], 'string', 'max' => 512],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Principals::className(), 'targetAttribute' => ['principal_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Teacher ID',
            'class_id' => 'Class ID',
            'kid_id' => 'Kid ID',
            'principal_id' => 'Principal ID',
            'title' => 'Title',
            'description' => 'Description',
            'bucket' => 'Bucket',
            'filepath' => 'Filepath',
            'filename' => 'Filename',
            'is_trivial' => 'Is Trivial',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumPhotos()
    {
        return $this->hasMany(AlbumPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckinPhotos()
    {
        return $this->hasMany(KidCheckinPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckoutPhotos()
    {
        return $this->hasMany(KidCheckoutPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportCommentPhotos()
    {
        return $this->hasMany(KidDailyReportCommentPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportPhotos()
    {
        return $this->hasMany(KidDailyReportPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageCommentPhotos()
    {
        return $this->hasMany(KidMessageCommentPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessagePhotos()
    {
        return $this->hasMany(KidMessagePhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidPrescriptionMedicinePhotos()
    {
        return $this->hasMany(KidPrescriptionMedicinePhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportCommentPhotos()
    {
        return $this->hasMany(KidWeeklyReportCommentPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportPhotos()
    {
        return $this->hasMany(KidWeeklyReportPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoCommentPhotos()
    {
        return $this->hasMany(PhotoCommentPhotos::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoComments()
    {
        return $this->hasMany(PhotoComments::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoKids()
    {
        return $this->hasMany(PhotoKids::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoLikes()
    {
        return $this->hasMany(PhotoLikes::className(), ['photo_id' => 'id']);
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
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipal()
    {
        return $this->hasOne(Principals::className(), ['id' => 'principal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProtectors()
    {
        return $this->hasMany(Protectors::className(), ['photo_id' => 'id']);
    }
}
