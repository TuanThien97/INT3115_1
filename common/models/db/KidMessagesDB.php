<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_messages".
 *
 * @property integer $id
 * @property integer $kid_id
 * @property integer $teacher_id
 * @property integer $direction_id
 * @property string $content
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidMessagesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_id', 'teacher_id', 'direction_id', 'content'], 'required'],
            [['kid_id', 'teacher_id', 'direction_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'teacher_id', 'direction_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDB::className(), 'targetAttribute' => ['kid_id' => 'id']],
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
            'kid_id' => 'Kid ID',
            'teacher_id' => 'Teacher ID',
            'direction_id' => 'Direction ID',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
