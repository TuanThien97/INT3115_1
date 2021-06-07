<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_daily_reports".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $teacher_id
 * @property string $day
 * @property string $content
 * @property int $kid_stamp_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidDailyReportComments[] $kidDailyReportComments
 * @property KidDailyReportLikes[] $kidDailyReportLikes
 * @property KidDailyReportPhotos[] $kidDailyReportPhotos
 * @property Classes $class
 * @property KidStamps $kidStamp
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidDailyReports extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_daily_reports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'teacher_id', 'day', 'content'], 'required'],
            [['kid_id', 'class_id', 'teacher_id', 'kid_stamp_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'teacher_id', 'kid_stamp_id', 'status'], 'integer'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['content'], 'string'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['kid_stamp_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidStamps::className(), 'targetAttribute' => ['kid_stamp_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
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
            'kid_id' => 'Kid ID',
            'class_id' => 'Class ID',
            'teacher_id' => 'Teacher ID',
            'day' => 'Day',
            'content' => 'Content',
            'kid_stamp_id' => 'Kid Stamp ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportComments()
    {
        return $this->hasMany(KidDailyReportComments::className(), ['kid_daily_report_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportLikes()
    {
        return $this->hasMany(KidDailyReportLikes::className(), ['kid_daily_report_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportPhotos()
    {
        return $this->hasMany(KidDailyReportPhotos::className(), ['kid_daily_report_id' => 'id']);
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
    public function getKidStamp()
    {
        return $this->hasOne(KidStamps::className(), ['id' => 'kid_stamp_id']);
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
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
