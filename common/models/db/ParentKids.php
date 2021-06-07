<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "parent_kids".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $kid_id
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $relation
 *
 * @property Kids $kid
 * @property Parents $parent
 */
class ParentKids extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parent_kids';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'kid_id'], 'required'],
            [['parent_id', 'kid_id', 'sequence', 'status'], 'default', 'value' => null],
            [['parent_id', 'kid_id', 'sequence', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['relation'], 'string', 'max' => 127],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parents::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'kid_id' => 'Kid ID',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'relation' => 'Relation',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kids::className(), ['id' => 'kid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Parents::className(), ['id' => 'parent_id']);
    }
}
