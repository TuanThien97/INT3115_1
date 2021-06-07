<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidPrescriptionMedicinePhotoFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidPrescriptionMedicinePhotos';
    public $depends = ['common\fixtures\KidPrescriptionMedicineFixture'];
    public $dataFile = '@common/fixtures/data/kid_prescription_medicine_photos.php';
}