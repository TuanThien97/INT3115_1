<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "api_teacher_modules".
 *
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $icon
 * @property int $level
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApiTeacherModuleRoutes[] $apiTeacherModuleRoutes
 * @property PackageRoleApiTeacherModules[] $packageRoleApiTeacherModules
 * @property PackageRoles[] $packageRoleNames
 */
class ApiTeacherModules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_teacher_modules';
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
            [['name', 'title'], 'required'],
            [['description'], 'string'],
            [['level', 'sequence', 'status'], 'default', 'value' => null],
            [['level', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'icon'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 256],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'title' => 'Title',
            'description' => 'Description',
            'icon' => 'Icon',
            'level' => 'Level',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiTeacherModuleRoutes()
    {
        return $this->hasMany(ApiTeacherModuleRoutes::className(), ['api_teacher_module_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleApiTeacherModules()
    {
        return $this->hasMany(PackageRoleApiTeacherModules::className(), ['api_teacher_module_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleNames()
    {
        return $this->hasMany(PackageRoles::className(), ['name' => 'package_role_name'])->viaTable('package_role_api_teacher_modules', ['api_teacher_module_name' => 'name']);
    }
}
