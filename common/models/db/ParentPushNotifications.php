<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "parent_push_notifications".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $push_type
 * @property string $pushed_at
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $push_content
 * @property string $push_data
 * @property int $kid_id
 * @property int $content_id
 *
 * @property Kids $kid
 * @property Parents $parent
 */
class ParentPushNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parent_push_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'push_type'], 'required'],
            [['parent_id', 'status', 'kid_id', 'content_id'], 'default', 'value' => null],
            [['parent_id', 'status', 'kid_id', 'content_id'], 'integer'],
            [['pushed_at', 'created_at', 'updated_at'], 'safe'],
            [['push_data'], 'string'],
            [['push_type'], 'string', 'max' => 32],
            [['push_content'], 'string', 'max' => 512],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parents::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'push_type' => 'Push Type',
            'pushed_at' => 'Pushed At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'push_content' => 'Push Content',
            'push_data' => 'Push Data',
            'kid_id' => 'Kid ID',
            'content_id' => 'Content ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Parents::className(), ['id' => 'parent_id']);
    }
}
