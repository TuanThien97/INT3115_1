<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidPrescriptionFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidPrescriptions';
    public $depends = ['common\fixtures\KidFixture'];
    public $dataFile = '@common/fixtures/data/kid_prescriptions.php';
}