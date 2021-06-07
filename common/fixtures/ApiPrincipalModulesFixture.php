<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ApiPrincipalModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ApiPrincipalModules';
    public $dataFile = '@common/fixtures/data/api_principal_modules.php';
}
