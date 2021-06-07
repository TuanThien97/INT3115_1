<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ProfessionalFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Professionals';
    public $dataFile = '@common/fixtures/data/professionals.php';
}
