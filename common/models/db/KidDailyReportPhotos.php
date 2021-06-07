<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_daily_report_photos".
 *
 * @property int $id
 * @property int $kid_daily_report_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidDailyReports $kidDailyReport
 * @property Photos $photo
 */
class KidDailyReportPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_daily_report_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_daily_report_id', 'photo_id'], 'required'],
            [['kid_daily_report_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_daily_report_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_daily_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidDailyReports::className(), 'targetAttribute' => ['kid_daily_report_id' => 'id']],
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
            'kid_daily_report_id' => 'Kid Daily Report ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
