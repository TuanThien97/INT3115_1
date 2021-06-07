<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "dealer_schools".
 *
 * @property integer $id
 * @property integer $dealer_id
 * @property integer $school_id
 * @property string $note
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class DealerSchoolsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealer_schools';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dealer_id', 'school_id'], 'required'],
            [['dealer_id', 'school_id', 'status'], 'default', 'value' => null],
            [['dealer_id', 'school_id', 'status'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['dealer_id'], 'exist', 'skipOnError' => true, 'targetClass' => DealersDB::className(), 'targetAttribute' => ['dealer_id' => 'id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolsDB::className(), 'targetAttribute' => ['school_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dealer_id' => 'Dealer ID',
            'school_id' => 'School ID',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
