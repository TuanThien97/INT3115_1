<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "photo_kids".
 *
 * @property integer $id
 * @property integer $photo_id
 * @property integer $kid_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PhotoKidsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo_kids';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_id', 'kid_id'], 'required'],
            [['photo_id', 'kid_id', 'status'], 'default', 'value' => null],
            [['photo_id', 'kid_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDB::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PhotosDB::className(), 'targetAttribute' => ['photo_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'photo_id' => 'Photo ID',
            'kid_id' => 'Kid ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
