<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_message_photos".
 *
 * @property int $id
 * @property int $kid_message_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidMessages $kidMessage
 * @property Photos $photo
 */
class KidMessagePhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_message_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_message_id', 'photo_id'], 'required'],
            [['kid_message_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_message_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidMessages::className(), 'targetAttribute' => ['kid_message_id' => 'id']],
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
            'kid_message_id' => 'Kid Message ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessage()
    {
        return $this->hasOne(KidMessages::className(), ['id' => 'kid_message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
