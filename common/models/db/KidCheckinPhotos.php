<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_checkin_photos".
 *
 * @property int $id
 * @property int $kid_checkin_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidCheckins $kidCheckin
 * @property Photos $photo
 */
class KidCheckinPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_checkin_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_checkin_id', 'photo_id'], 'required'],
            [['kid_checkin_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_checkin_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_checkin_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidCheckins::className(), 'targetAttribute' => ['kid_checkin_id' => 'id']],
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
            'kid_checkin_id' => 'Kid Checkin ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckin()
    {
        return $this->hasOne(KidCheckins::className(), ['id' => 'kid_checkin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
