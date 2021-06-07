<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "photo_comments".
 *
 * @property integer $id
 * @property integer $photo_id
 * @property integer $sender_id
 * @property integer $direction_id
 * @property string $content
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PhotoCommentsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_id', 'sender_id', 'direction_id', 'content'], 'required'],
            [['photo_id', 'sender_id', 'direction_id', 'status'], 'default', 'value' => null],
            [['photo_id', 'sender_id', 'direction_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PhotosDB::className(), 'targetAttribute' => ['photo_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'photo_id' => 'Photo ID',
            'sender_id' => 'Sender ID',
            'direction_id' => 'Direction ID',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
