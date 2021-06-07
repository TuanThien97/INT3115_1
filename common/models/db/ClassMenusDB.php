<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_menus".
 *
 * @property integer $id
 * @property integer $class_id
 * @property string $day
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ClassMenusDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'day'], 'required'],
            [['class_id', 'status'], 'default', 'value' => null],
            [['class_id', 'status'], 'integer'],
            [['day', 'created_at', 'updated_at'], 'safe'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClassesDB::className(), 'targetAttribute' => ['class_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'day' => 'Day',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
