<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_timetables".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $dow
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ClassTimetablesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_timetables';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'dow'], 'required'],
            [['class_id', 'dow', 'status'], 'default', 'value' => null],
            [['class_id', 'dow', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'dow' => 'Dow',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
