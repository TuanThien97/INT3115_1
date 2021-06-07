<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "packages".
 *
 * @property string $name
 * @property string $title
 * @property string $description
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PackageRoles[] $packageRoles
 * @property SchoolPackages[] $schoolPackages
 */
class Packages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages';
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
            [['sequence', 'status'], 'default', 'value' => null],
            [['sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
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
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageRoles()
    {
        return $this->hasMany(PackageRoles::className(), ['package_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolPackages()
    {
        return $this->hasMany(SchoolPackages::className(), ['package_name' => 'name']);
    }
}
