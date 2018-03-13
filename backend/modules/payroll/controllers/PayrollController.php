<?php

namespace backend\modules\payroll\controllers;

class PayrollController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
