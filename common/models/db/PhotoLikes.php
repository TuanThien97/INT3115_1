<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "photo_likes".
 *
 * @property int $id
 * @property int $photo_id
 * @property int $teacher_id
 * @property int $kid_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Kids $kid
 * @property Photos $photo
 * @property Teachers $teacher
 */
class PhotoLikes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photo_likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['photo_id'], 'required'],
            [['photo_id', 'teacher_id', 'kid_id', 'status'], 'default', 'value' => null],
            [['photo_id', 'teacher_id', 'kid_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
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
            'photo_id' => 'Photo ID',
            'teacher_id' => 'Teacher ID',
            'kid_id' => 'Kid ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
