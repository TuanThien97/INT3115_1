<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "app_versions".
 *
 * @property string $app_version
 * @property string $app_id
 * @property string $note
 * @property int $is_update_mandatory
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class AppVersions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_versions';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_version', 'app_id'], 'required'],
            [['note'], 'string'],
            [['is_update_mandatory', 'status'], 'default', 'value' => null],
            [['is_update_mandatory', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['app_version', 'app_id'], 'string', 'max' => 12],
            [['app_version', 'app_id'], 'unique', 'targetAttribute' => ['app_version', 'app_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'app_version' => 'App Version',
            'app_id' => 'App ID',
            'note' => 'Note',
            'is_update_mandatory' => 'Is Update Mandatory',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
