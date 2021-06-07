<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "absence_reasons".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KidAbsences[] $kidAbsences
 */
class AbsenceReasons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'absence_reasons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'sequence'], 'required'],
            [['description'], 'string'],
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidAbsences()
    {
        return $this->hasMany(KidAbsences::className(), ['absence_reason_id' => 'id']);
    }
}
