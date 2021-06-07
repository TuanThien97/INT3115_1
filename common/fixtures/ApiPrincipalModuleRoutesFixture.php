<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ApiPrincipalModuleRoutesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ApiPrincipalModuleRoutes';
    public $depends = ['common\fixtures\ApiPrincipalModulesFixture'];
    public $dataFile = '@common/fixtures/data/api_principal_module_routes.php';
}
