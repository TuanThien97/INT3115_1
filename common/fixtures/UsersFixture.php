<?php
namespace common\fixtures;

use yii\test\ActiveFixture;

class UsersFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Users';
    public $dataFile = '@common/fixtures/data/users.php';
}