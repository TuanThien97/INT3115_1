<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_message_likes".
 *
 * @property integer $id
 * @property integer $kid_message_id
 * @property integer $sender_id
 * @property integer $direction_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidMessageLikesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_message_likes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_message_id', 'sender_id', 'direction_id'], 'required'],
            [['kid_message_id', 'sender_id', 'direction_id', 'status'], 'default', 'value' => null],
            [['kid_message_id', 'sender_id', 'direction_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidMessagesDB::className(), 'targetAttribute' => ['kid_message_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_message_id' => 'Kid Message ID',
            'sender_id' => 'Sender ID',
            'direction_id' => 'Direction ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
