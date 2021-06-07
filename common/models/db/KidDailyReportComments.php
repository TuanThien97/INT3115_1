<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_daily_report_comments".
 *
 * @property int $id
 * @property int $kid_daily_report_id
 * @property int $teacher_id
 * @property int $kid_id
 * @property string $content
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidDailyReportCommentPhotos[] $kidDailyReportCommentPhotos
 * @property KidDailyReports $kidDailyReport
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidDailyReportComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_daily_report_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_daily_report_id', 'content'], 'required'],
            [['kid_daily_report_id', 'teacher_id', 'kid_id', 'status'], 'default', 'value' => null],
            [['kid_daily_report_id', 'teacher_id', 'kid_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_daily_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidDailyReports::className(), 'targetAttribute' => ['kid_daily_report_id' => 'id']],
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
            'kid_daily_report_id' => 'Kid Daily Report ID',
            'teacher_id' => 'Teacher ID',
            'kid_id' => 'Kid ID',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportCommentPhotos()
    {
        return $this->hasMany(KidDailyReportCommentPhotos::className(), ['kid_daily_report_comment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReport()
    {
        return $this->hasOne(KidDailyReports::className(), ['id' => 'kid_daily_report_id']);
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
