<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "weekly_report_content_templates".
 *
 * @property int $id
 * @property string $content
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class WeeklyReportContentTemplates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'weekly_report_content_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'sequence'], 'required'],
            [['content'], 'string'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
