<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "kid_school_services".
 *
 * @property integer $id
 * @property integer $kid_id
 * @property integer $school_service_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class KidSchoolServicesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kid_school_services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid_id', 'school_service_id'], 'required'],
            [['kid_id', 'school_service_id', 'status'], 'default', 'value' => null],
            [['kid_id', 'school_service_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsDB::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['school_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolServicesDB::className(), 'targetAttribute' => ['school_service_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid_id' => 'Kid ID',
            'school_service_id' => 'School Service ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
