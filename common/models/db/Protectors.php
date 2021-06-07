<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "protectors".
 *
 * @property int $id
 * @property int $kid_id
 * @property string $first_name
 * @property string $last_name
 * @property int $relationship_id
 * @property int $gender
 * @property int $yob
 * @property int $photo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $code
 * @property string $qrcode
 *
 * @property KidCheckouts[] $kidCheckouts
 * @property Kids $kid
 * @property Photos $photo
 * @property Relationships $relationship
 */
class Protectors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'protectors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid_id', 'first_name', 'last_name', 'relationship_id'], 'required'],
            [['kid_id', 'relationship_id', 'gender', 'yob', 'photo_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'relationship_id', 'gender', 'yob', 'photo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['qrcode'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['code'], 'string', 'max' => 16],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
            [['relationship_id'], 'exist', 'skipOnError' => true, 'targetClass' => Relationships::className(), 'targetAttribute' => ['relationship_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_id' => 'Kid ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'relationship_id' => 'Relationship ID',
            'gender' => 'Gender',
            'yob' => 'Yob',
            'photo_id' => 'Photo ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidCheckouts()
    {
        return $this->hasMany(KidCheckouts::className(), ['protector_id' => 'id']);
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
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationship()
    {
        return $this->hasOne(Relationships::className(), ['id' => 'relationship_id']);
    }
}
