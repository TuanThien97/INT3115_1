<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "jwts".
 *
 * @property string $jid
 * @property string $app_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class JwtsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jwts';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jid', 'app_id'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['jid'], 'string', 'max' => 16],
            [['app_id'], 'string', 'max' => 32],
            [['jid'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jid' => 'Jid',
            'app_id' => 'App ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
