<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "album_photos".
 *
 * @property int $id
 * @property int $album_id
 * @property int $photo_id
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Albums $album
 * @property Photos $photo
 */
class AlbumPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'album_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['album_id', 'photo_id'], 'required'],
            [['album_id', 'photo_id', 'sequence', 'status'], 'default', 'value' => null],
            [['album_id', 'photo_id', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['album_id'], 'exist', 'skipOnError' => true, 'targetClass' => Albums::className(), 'targetAttribute' => ['album_id' => 'id']],
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
            'album_id' => 'Album ID',
            'photo_id' => 'Photo ID',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(Albums::className(), ['id' => 'album_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
