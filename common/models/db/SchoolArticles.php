<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_articles".
 *
 * @property int $id
 * @property int $school_id
 * @property string $title
 * @property string $content
 * @property int $photo_id
 * @property int $total_view
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolArticleSendLogs[] $schoolArticleSendLogs
 * @property Photos $photo
 * @property Schools $school
 */
class SchoolArticles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'title', 'content'], 'required'],
            [['school_id', 'photo_id', 'total_view', 'status'], 'default', 'value' => null],
            [['school_id', 'photo_id', 'total_view', 'status'], 'integer'],
            [['title', 'content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
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
            'title' => 'Title',
            'content' => 'Content',
            'photo_id' => 'Photo ID',
            'total_view' => 'Total View',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolArticleSendLogs()
    {
        return $this->hasMany(SchoolArticleSendLogs::className(), ['school_article_id' => 'id']);
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
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }
}
