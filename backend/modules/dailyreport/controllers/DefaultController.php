<?php

namespace backend\modules\dailyreport\controllers;

use yii\web\Controller;

/**
 * Default controller for the `dailyreport` module
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
