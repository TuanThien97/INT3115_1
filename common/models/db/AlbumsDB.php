<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "albums".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $school_event_id
 */
class AlbumsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'albums';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'school_event_id'], 'required'],
            [['description'], 'string'],
            [['status', 'school_event_id'], 'default', 'value' => null],
            [['status', 'school_event_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['school_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolEventsDB::className(), 'targetAttribute' => ['school_event_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'school_event_id' => 'School Event ID',
        ];
    }
}
