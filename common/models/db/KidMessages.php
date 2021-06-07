<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_messages".
 *
 * @property int $id
 * @property int $kid_id
 * @property int $class_id
 * @property int $teacher_id
 * @property string $content
 * @property int $is_from_kid
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $message_date
 *
 * @property KidMessageComments[] $kidMessageComments
 * @property KidMessageLikes[] $kidMessageLikes
 * @property KidMessagePhotos[] $kidMessagePhotos
 * @property Classes $class
 * @property Kids $kid
 * @property Teachers $teacher
 */
class KidMessages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'class_id', 'teacher_id', 'content'], 'required'],
            [['kid_id', 'class_id', 'teacher_id', 'is_from_kid', 'status'], 'default', 'value' => null],
            [['kid_id', 'class_id', 'teacher_id', 'is_from_kid', 'status'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'message_date'], 'safe'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
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
            'kid_id' => 'Kid ID',
            'class_id' => 'Class ID',
            'teacher_id' => 'Teacher ID',
            'content' => 'Content',
            'is_from_kid' => 'Is From Kid',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'message_date' => 'Message Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageComments()
    {
        return $this->hasMany(KidMessageComments::className(), ['kid_message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessageLikes()
    {
        return $this->hasMany(KidMessageLikes::className(), ['kid_message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidMessagePhotos()
    {
        return $this->hasMany(KidMessagePhotos::className(), ['kid_message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
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
