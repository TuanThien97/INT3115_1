<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidWeeklyReportFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidWeeklyReports';
    public $depends = ['common\fixtures\KidFixture', 'common\fixtures\TeacherFixture', 'common\fixtures\KidStampFixture'];
    public $dataFile = '@common/fixtures/data/kid_weekly_reports.php';
}