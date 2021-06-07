<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ApiParentModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ApiParentModules';
    public $dataFile = '@common/fixtures/data/api_parent_modules.php';
}
