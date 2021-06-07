<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "api_teacher_module_routes".
 *
 * @property int $id
 * @property string $api_teacher_module_name
 * @property string $route
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApiTeacherModules $apiTeacherModuleName
 */
class ApiTeacherModuleRoutes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_teacher_module_routes';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['api_teacher_module_name', 'route'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['api_teacher_module_name', 'route'], 'string', 'max' => 64],
            [['api_teacher_module_name'], 'exist', 'skipOnError' => true, 'targetClass' => ApiTeacherModules::className(), 'targetAttribute' => ['api_teacher_module_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'api_teacher_module_name' => 'Api Teacher Module Name',
            'route' => 'Route',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiTeacherModuleName()
    {
        return $this->hasOne(ApiTeacherModules::className(), ['name' => 'api_teacher_module_name']);
    }
}
