<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_message_comments".
 *
 * @property integer $id
 * @property integer $kid_message_id
 * @property integer $sender_id
 * @property integer $direction_id
 * @property string $content
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $parent_id
 * @property integer $teacher_id
 */
class KidMessageCommentsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_message_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_message_id', 'sender_id', 'direction_id', 'content', 'parent_id', 'teacher_id'], 'required'],
            [['kid_message_id', 'sender_id', 'direction_id', 'status', 'parent_id', 'teacher_id'], 'default', 'value' => null],
            [['kid_message_id', 'sender_id', 'direction_id', 'status', 'parent_id', 'teacher_id'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidMessagesDB::className(), 'targetAttribute' => ['kid_message_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ParentsDB::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'kid_message_id' => 'Kid Message ID',
            'sender_id' => 'Sender ID',
            'direction_id' => 'Direction ID',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'parent_id' => 'Parent ID',
            'teacher_id' => 'Teacher ID',
        ];
    }
}
