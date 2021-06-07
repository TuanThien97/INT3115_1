<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teacher_settings".
 *
 * @property int $id
 * @property int $teacher_id
 * @property string $option
 * @property string $value
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Teachers $teacher
 */
class TeacherSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'option', 'value'], 'required'],
            [['teacher_id', 'status'], 'default', 'value' => null],
            [['teacher_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['option', 'value'], 'string', 'max' => 64],
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
            'teacher_id' => 'Teacher ID',
            'option' => 'Option',
            'value' => 'Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
