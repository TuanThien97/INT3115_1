<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_menus".
 *
 * @property int $id
 * @property int $class_id
 * @property int $school_menu_id
 * @property string $day
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Classes $class
 * @property SchoolMenus $schoolMenu
 */
class ClassMenus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_menus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'school_menu_id', 'day'], 'required'],
            [['class_id', 'school_menu_id', 'status'], 'default', 'value' => null],
            [['class_id', 'school_menu_id', 'status'], 'integer'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['school_menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolMenus::className(), 'targetAttribute' => ['school_menu_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'school_menu_id' => 'School Menu ID',
            'day' => 'Day',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolMenu()
    {
        return $this->hasOne(SchoolMenus::className(), ['id' => 'school_menu_id']);
    }
}
