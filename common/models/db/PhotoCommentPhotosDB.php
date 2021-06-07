<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "photo_comment_photos".
 *
 * @property integer $id
 * @property integer $photo_comment_id
 * @property integer $photo_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PhotoCommentPhotosDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo_comment_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_comment_id', 'photo_id'], 'required'],
            [['photo_comment_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['photo_comment_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['photo_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => PhotoCommentsDB::className(), 'targetAttribute' => ['photo_comment_id' => 'id']],
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
            'photo_comment_id' => 'Photo Comment ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
