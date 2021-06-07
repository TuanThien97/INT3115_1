<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "albums".
 *
 * @property int $id
 * @property int $school_id
 * @property int $teacher_id
 * @property int $principal_id
 * @property int $class_id
 * @property string $name
 * @property string $description
 * @property int $school_event_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AlbumComments[] $albumComments
 * @property AlbumLikes[] $albumLikes
 * @property AlbumPhotos[] $albumPhotos
 * @property Classes $class
 * @property SchoolEvents $schoolEvent
 * @property Schools $school
 * @property Teachers $teacher
 * @property Teachers $principal
 */
class Albums extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'albums';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'name'], 'required'],
            [['school_id', 'teacher_id', 'principal_id', 'class_id', 'school_event_id', 'status'], 'default', 'value' => null],
            [['school_id', 'teacher_id', 'principal_id', 'class_id', 'school_event_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['school_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolEvents::className(), 'targetAttribute' => ['school_event_id' => 'id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schools::className(), 'targetAttribute' => ['school_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['principal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'teacher_id' => 'Teacher ID',
            'principal_id' => 'Principal ID',
            'class_id' => 'Class ID',
            'name' => 'Name',
            'description' => 'Description',
            'school_event_id' => 'School Event ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumComments()
    {
        return $this->hasMany(AlbumComments::className(), ['album_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumLikes()
    {
        return $this->hasMany(AlbumLikes::className(), ['album_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumPhotos()
    {
        return $this->hasMany(AlbumPhotos::className(), ['album_id' => 'id']);
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
    public function getSchoolEvent()
    {
        return $this->hasOne(SchoolEvents::className(), ['id' => 'school_event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipal()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'principal_id']);
    }
}
