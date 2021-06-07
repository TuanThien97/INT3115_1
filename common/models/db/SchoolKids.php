<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_kids".
 *
 * @property int $id
 * @property int $school_id
 * @property int $kid_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $entered_at
 * @property string $exited_at
 *
 * @property Kids $kid
 * @property Schools $school
 */
class SchoolKids extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_kids';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'kid_id'], 'required'],
            [['school_id', 'kid_id', 'status'], 'default', 'value' => null],
            [['school_id', 'kid_id', 'status'], 'integer'],
            [['created_at', 'updated_at', 'entered_at', 'exited_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schools::className(), 'targetAttribute' => ['school_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'kid_id' => 'Kid ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'entered_at' => 'Entered At',
            'exited_at' => 'Exited At',
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
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }
}
