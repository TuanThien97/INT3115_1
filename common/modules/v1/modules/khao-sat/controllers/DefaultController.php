<?php

namespace common\modules\v10\modules\survey\controllers;

use yii\web\Controller;

/**
 * Default controller for the `survey` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
