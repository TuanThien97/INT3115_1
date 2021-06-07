<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_notifications".
 *
 * @property int $id
 * @property int $principal_id
 * @property string $content
 * @property string $data
 * @property int $is_mail
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Principals $principal
 */
class PrincipalNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'principal_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['principal_id', 'content', 'data', 'is_mail'], 'required'],
            [['principal_id', 'is_mail', 'status'], 'default', 'value' => null],
            [['principal_id', 'is_mail', 'status'], 'integer'],
            [['content', 'data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Principals::className(), 'targetAttribute' => ['principal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'principal_id' => 'Principal ID',
            'content' => 'Content',
            'data' => 'Data',
            'is_mail' => 'Is Mail',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipal()
    {
        return $this->hasOne(Principals::className(), ['id' => 'principal_id']);
    }
}
