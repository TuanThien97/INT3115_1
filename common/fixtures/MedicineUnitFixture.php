<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class MedicineUnitFixture extends ActiveFixture
{
    public $modelClass = 'common\models\MedicineUnits';
    public $dataFile = '@common/fixtures/data/medicine_units.php';
}