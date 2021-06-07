<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ApiParentModuleRoutesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ApiParentModuleRoutes';
    public $depends = ['common\fixtures\ApiParentModulesFixture'];
    public $dataFile = '@common/fixtures/data/api_parent_module_routes.php';
}
