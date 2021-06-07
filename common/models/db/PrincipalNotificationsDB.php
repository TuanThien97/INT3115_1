<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_notifications".
 *
 * @property integer $id
 * @property integer $principal_id
 * @property string $content
 * @property string $data
 * @property integer $is_active
 * @property integer $is_mail
 * @property string $created_at
 * @property string $updated_at
 */
class PrincipalNotificationsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'principal_notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['principal_id', 'content', 'data', 'is_active', 'is_mail'], 'required'],
            [['principal_id', 'is_active', 'is_mail'], 'default', 'value' => null],
            [['principal_id', 'is_active', 'is_mail'], 'integer'],
            [['content', 'data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrincipalsDB::className(), 'targetAttribute' => ['principal_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'principal_id' => 'Principal ID',
            'content' => 'Content',
            'data' => 'Data',
            'is_active' => 'Is Active',
            'is_mail' => 'Is Mail',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
