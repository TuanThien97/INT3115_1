<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_daily_report_comment_photos".
 *
 * @property int $id
 * @property int $kid_daily_report_comment_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidDailyReportComments $kidDailyReportComment
 * @property Photos $photo
 */
class KidDailyReportCommentPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_daily_report_comment_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_daily_report_comment_id', 'photo_id'], 'required'],
            [['kid_daily_report_comment_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_daily_report_comment_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_daily_report_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidDailyReportComments::className(), 'targetAttribute' => ['kid_daily_report_comment_id' => 'id']],
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
            'kid_daily_report_comment_id' => 'Kid Daily Report Comment ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReportComment()
    {
        return $this->hasOne(KidDailyReportComments::className(), ['id' => 'kid_daily_report_comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
