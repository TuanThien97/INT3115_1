<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_weekly_report_photos".
 *
 * @property int $id
 * @property int $kid_weekly_report_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidWeeklyReports $kidWeeklyReport
 * @property Photos $photo
 */
class KidWeeklyReportPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_weekly_report_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_weekly_report_id', 'photo_id'], 'required'],
            [['kid_weekly_report_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_weekly_report_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_weekly_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidWeeklyReports::className(), 'targetAttribute' => ['kid_weekly_report_id' => 'id']],
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
            'kid_weekly_report_id' => 'Kid Weekly Report ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReport()
    {
        return $this->hasOne(KidWeeklyReports::className(), ['id' => 'kid_weekly_report_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
