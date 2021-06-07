<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_stamps".
 *
 * @property int $id
 * @property string $name
 * @property string $photo_url
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidDailyReports[] $kidDailyReports
 * @property KidWeeklyReports[] $kidWeeklyReports
 */
class KidStamps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_stamps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'photo_url', 'sequence'], 'required'],
            [['photo_url'], 'string'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
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
            'photo_url' => 'Photo Url',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidDailyReports()
    {
        return $this->hasMany(KidDailyReports::className(), ['kid_stamp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidWeeklyReports()
    {
        return $this->hasMany(KidWeeklyReports::className(), ['kid_stamp_id' => 'id']);
    }
}
