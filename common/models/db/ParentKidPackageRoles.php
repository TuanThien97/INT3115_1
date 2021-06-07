<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "parent_kid_package_roles".
 *
 * @property int $parent_id
 * @property int $kid_id
 * @property int $school_id
 * @property string $package_role_name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PackageRoles $packageRoleName
 */
class ParentKidPackageRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parent_kid_package_roles';
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
            [['parent_id', 'kid_id', 'school_id', 'package_role_name'], 'required'],
            [['parent_id', 'kid_id', 'school_id', 'status'], 'default', 'value' => null],
            [['parent_id', 'kid_id', 'school_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['package_role_name'], 'string', 'max' => 64],
            [['parent_id', 'kid_id', 'school_id', 'package_role_name'], 'unique', 'targetAttribute' => ['parent_id', 'kid_id', 'school_id', 'package_role_name']],
            [['package_role_name'], 'exist', 'skipOnError' => true, 'targetClass' => PackageRoles::className(), 'targetAttribute' => ['package_role_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => 'Parent ID',
            'kid_id' => 'Kid ID',
            'school_id' => 'School ID',
            'package_role_name' => 'Package Role Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoleName()
    {
        return $this->hasOne(PackageRoles::className(), ['name' => 'package_role_name']);
    }
}
