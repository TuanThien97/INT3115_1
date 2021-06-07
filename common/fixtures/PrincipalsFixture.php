<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PrincipalsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Principals';
    public $depends = ['common\fixtures\ProfessionalFixture', 'common\fixtures\IncomingLevelFixture'];
    public $dataFile = '@common/fixtures/data/principals.php';
}
