<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_message_comment_photos".
 *
 * @property integer $id
 * @property integer $kid_message_comment_id
 * @property integer $photo_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidMessageCommentPhotosDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_message_comment_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_message_comment_id', 'photo_id'], 'required'],
            [['kid_message_comment_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_message_comment_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_message_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidMessageCommentsDB::className(), 'targetAttribute' => ['kid_message_comment_id' => 'id']],
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
            'kid_message_comment_id' => 'Kid Message Comment ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
