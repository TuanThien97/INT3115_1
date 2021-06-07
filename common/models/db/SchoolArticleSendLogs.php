<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_article_send_logs".
 *
 * @property int $id
 * @property int $school_article_id
 * @property string $type
 * @property int $receiver_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolArticles $schoolArticle
 */
class SchoolArticleSendLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_article_send_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_article_id', 'type'], 'required'],
            [['school_article_id', 'receiver_id', 'status'], 'default', 'value' => null],
            [['school_article_id', 'receiver_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 32],
            [['school_article_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolArticles::className(), 'targetAttribute' => ['school_article_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_article_id' => 'School Article ID',
            'type' => 'Type',
            'receiver_id' => 'Receiver ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolArticle()
    {
        return $this->hasOne(SchoolArticles::className(), ['id' => 'school_article_id']);
    }
}
