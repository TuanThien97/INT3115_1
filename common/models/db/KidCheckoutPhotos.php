<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_checkout_photos".
 *
 * @property int $id
 * @property int $kid_checkout_id
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidCheckouts $kidCheckout
 * @property Photos $photo
 */
class KidCheckoutPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_checkout_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_checkout_id', 'photo_id'], 'required'],
            [['kid_checkout_id', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_checkout_id', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_checkout_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidCheckouts::className(), 'targetAttribute' => ['kid_checkout_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_checkout_id' => 'Kid Checkout ID',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckout()
    {
        return $this->hasOne(KidCheckouts::className(), ['id' => 'kid_checkout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
