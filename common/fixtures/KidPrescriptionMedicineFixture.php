<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidPrescriptionMedicineFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidPrescriptionMedicines';
    public $depends = ['common\fixtures\KidPrescriptionFixture', 'common\fixtures\MedicineUnitFixture'];
    public $dataFile = '@common/fixtures/data/kid_prescription_medicines.php';
}