<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "schools".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $district_id
 * @property string $location
 * @property string $phone
 * @property string $email
 * @property string $working_time
 * @property string $code
 * @property string $qrcode
 * @property string $slug
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DealerSchools[] $dealerSchools
 * @property PrincipalSchools[] $principalSchools
 * @property SchoolArticles[] $schoolArticles
 * @property SchoolEvents[] $schoolEvents
 * @property SchoolGrades[] $schoolGrades
 * @property SchoolKids[] $schoolKids
 * @property SchoolMenus[] $schoolMenuses
 * @property SchoolPrivateCourses[] $schoolPrivateCourses
 * @property SchoolServices[] $schoolServices
 * @property SchoolTeachers[] $schoolTeachers
 * @property Districts $district
 */
class Schools extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schools';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address', 'location', 'phone', 'email', 'working_time', 'code', 'qrcode', 'slug'], 'required'],
            [['address', 'working_time', 'qrcode'], 'string'],
            [['district_id', 'status'], 'default', 'value' => null],
            [['district_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['location'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 32],
            [['email', 'slug'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 16],
            [['code'], 'unique'],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['qrcode'], 'unique'],
            [['slug'], 'unique'],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => Districts::className(), 'targetAttribute' => ['district_id' => 'id']],
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
            'address' => 'Address',
            'district_id' => 'District ID',
            'location' => 'Location',
            'phone' => 'Phone',
            'email' => 'Email',
            'working_time' => 'Working Time',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
            'slug' => 'Slug',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealerSchools()
    {
        return $this->hasMany(DealerSchools::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipalSchools()
    {
        return $this->hasMany(PrincipalSchools::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolArticles()
    {
        return $this->hasMany(SchoolArticles::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolEvents()
    {
        return $this->hasMany(SchoolEvents::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolGrades()
    {
        return $this->hasMany(SchoolGrades::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolKids()
    {
        return $this->hasMany(SchoolKids::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMenuses()
    {
        return $this->hasMany(SchoolMenus::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolPrivateCourses()
    {
        return $this->hasMany(SchoolPrivateCourses::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolServices()
    {
        return $this->hasMany(SchoolServices::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolTeachers()
    {
        return $this->hasMany(SchoolTeachers::className(), ['school_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(Districts::className(), ['id' => 'district_id']);
    }
}
