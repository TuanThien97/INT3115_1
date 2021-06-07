<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PrincipalSchoolsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\PrincipalSchools';
    //public $depends = ['common\fixtures\ProfessionalFixture', 'common\fixtures\IncomingLevelFixture'];
    public $dataFile = '@common/fixtures/data/principal_schools.php';
}
