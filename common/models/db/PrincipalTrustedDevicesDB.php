<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_trusted_devices".
 *
 * @property string $guid
 * @property integer $principal_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PrincipalTrustedDevicesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'principal_trusted_devices';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    // primary key definition 
    public static function primaryKey()
    {
        return ['guid'];
    } 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'principal_id'], 'required'],
            [['principal_id', 'status'], 'default', 'value' => null],
            [['principal_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['guid'], 'string', 'max' => 40],
            [['guid'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'guid' => 'Guid',
            'principal_id' => 'Principal ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
