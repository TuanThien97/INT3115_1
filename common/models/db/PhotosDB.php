<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "photos".
 *
 * @property integer $id
 * @property integer $album_id
 * @property string $title
 * @property string $description
 * @property string $photo_url
 * @property integer $width
 * @property integer $height
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $teacher_id
 */
class PhotosDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_id', 'title', 'description', 'photo_url', 'width', 'height', 'teacher_id'], 'required'],
            [['album_id', 'width', 'height', 'status', 'teacher_id'], 'default', 'value' => null],
            [['album_id', 'width', 'height', 'status', 'teacher_id'], 'integer'],
            [['title', 'description', 'photo_url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['album_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlbumsDB::className(), 'targetAttribute' => ['album_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeachersDB::className(), 'targetAttribute' => ['teacher_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeachersDB::className(), 'targetAttribute' => ['teacher_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'title' => 'Title',
            'description' => 'Description',
            'photo_url' => 'Photo Url',
            'width' => 'Width',
            'height' => 'Height',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'teacher_id' => 'Teacher ID',
        ];
    }
}
