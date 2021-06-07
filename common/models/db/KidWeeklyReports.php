<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_weekly_reports".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $teacher_id
 * @property int $week
 * @property int $year
 * @property string $start_date
 * @property string $last_date
 * @property string $content
 * @property int $kid_stamp_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidWeeklyReportComments[] $kidWeeklyReportComments
 * @property KidWeeklyReportLikes[] $kidWeeklyReportLikes
 * @property KidWeeklyReportPhotos[] $kidWeeklyReportPhotos
 * @property Classes $class
 * @property KidStamps $kidStamp
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidWeeklyReports extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_weekly_reports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'teacher_id', 'week', 'year', 'content'], 'required'],
            [['kid_id', 'class_id', 'teacher_id', 'week', 'year', 'kid_stamp_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'teacher_id', 'week', 'year', 'kid_stamp_id', 'status'], 'integer'],
            [['start_date', 'last_date', 'created_at', 'updated_at'], 'safe'],
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
            'week' => 'Week',
            'year' => 'Year',
            'start_date' => 'Start Date',
            'last_date' => 'Last Date',
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
    public function getKidWeeklyReportComments()
    {
        return $this->hasMany(KidWeeklyReportComments::className(), ['kid_weekly_report_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportLikes()
    {
        return $this->hasMany(KidWeeklyReportLikes::className(), ['kid_weekly_report_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReportPhotos()
    {
        return $this->hasMany(KidWeeklyReportPhotos::className(), ['kid_weekly_report_id' => 'id']);
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
