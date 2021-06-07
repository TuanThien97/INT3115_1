<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_events".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $description
 * @property string $started_date
 * @property string $finished_date
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Albums[] $albums
 * @property Schools $school
 */
class SchoolEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'name', 'started_date', 'finished_date'], 'required'],
            [['school_id', 'status'], 'default', 'value' => null],
            [['school_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['started_date', 'finished_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schools::className(), 'targetAttribute' => ['school_id' => 'id']],
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
            'name' => 'Name',
            'description' => 'Description',
            'started_date' => 'Started Date',
            'finished_date' => 'Finished Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Albums::className(), ['school_event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }
}
