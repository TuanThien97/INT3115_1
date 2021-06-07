<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidDailyReportFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidDailyReports';
    public $depends = ['common\fixtures\KidFixture', 'common\fixtures\TeacherFixture', 'common\fixtures\KidStampFixture'];
    public $dataFile = '@common/fixtures/data/kid_daily_reports.php';
}