<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_message_comments".
 *
 * @property int $id
 * @property int $kid_message_id
 * @property int $kid_id
 * @property int $teacher_id
 * @property string $content
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidMessages $kidMessage
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidMessageComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_message_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_message_id', 'content'], 'required'],
            [['kid_message_id', 'kid_id', 'teacher_id', 'status'], 'default', 'value' => null],
            [['kid_message_id', 'kid_id', 'teacher_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidMessages::className(), 'targetAttribute' => ['kid_message_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
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
            'kid_id' => 'Kid ID',
            'teacher_id' => 'Teacher ID',
            'content' => 'Content',
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
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
