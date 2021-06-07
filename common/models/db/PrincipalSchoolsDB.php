<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_schools".
 *
 * @property integer $id
 * @property integer $principal_id
 * @property integer $school_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PrincipalSchoolsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'principal_schools';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['principal_id', 'school_id'], 'required'],
            [['principal_id', 'school_id', 'status'], 'default', 'value' => null],
            [['principal_id', 'school_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrincipalsDB::className(), 'targetAttribute' => ['principal_id' => 'id']],
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
            'principal_id' => 'Principal ID',
            'school_id' => 'School ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
