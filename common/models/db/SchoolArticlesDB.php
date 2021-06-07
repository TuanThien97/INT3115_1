<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_articles".
 *
 * @property integer $id
 * @property integer $school_id
 * @property string $title
 * @property string $content
 * @property string $photo_url
 * @property integer $total_view
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class SchoolArticlesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'title', 'content', 'photo_url', 'total_view'], 'required'],
            [['school_id', 'total_view', 'status'], 'default', 'value' => null],
            [['school_id', 'total_view', 'status'], 'integer'],
            [['title', 'content', 'photo_url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolsDB::className(), 'targetAttribute' => ['school_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'title' => 'Title',
            'content' => 'Content',
            'photo_url' => 'Photo Url',
            'total_view' => 'Total View',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
