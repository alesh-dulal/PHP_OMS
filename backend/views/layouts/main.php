<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => ['/dashboard/default'],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
  

    $menuItems = [
        ['label' => 'Home', 'url' => ['/dashboard/default']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else if  (strtolower(Yii::$app->session['Role'])== 'admin' || strtolower(Yii::$app->session['Role'])== 'superadmin'){
        $menuItems = [
         ['label' => 'MyInfo', 'url' => ['/user/myinfo/myinfo']],
         ['label' => 'Report', 'url' => ['/dailyreport/dailyreport/report']],
        ['label' => 'Stock', 'url' => ['/stock/stock/index']],
        [
            'label' => 'Payroll',
            'items' => [
                 ['label' => 'Advance Salary', 'url' => '/oms/payroll/advance'],
                 ['label' => 'Payroll Setting', 'url' => '/oms/payroll/payrollsetting'],
                 ['label' => 'Payroll Attendance', 'url' => '/oms/payroll/payrollattendance'],
                 ['label' => 'Designation Salary', 'url' => '/oms/payroll/designationsalary'],
                 ['label' => 'Employee Payroll', 'url' => '/oms/payroll/employeepayroll'],
                 ['label' => 'Payroll', 'url' => '/oms/payroll/payroll'],
                 ['label' => 'Article Based', 'url' => '/oms/payroll/virtualpayroll'],
                 ['label' => 'Pay & Terminate', 'url' => '/oms/payroll/payandterminate'],
            ],
        ],
        ['label' => 'Attendance', 'url' => ['/attendance/attendance/index']],
        ['label' => 'Employee', 'url' => ['/user/employee/index']],
        ['label' => 'Leave', 'url' => ['/leave/leave/index']],
        ['label' => 'Settings', 'url' => ['/user/settings']],
        ['label' => 'Daily Report', 'url' => ['/dailyreport/dailyreport/index']],
        ['label' => Yii::$app->session['FullName'],
               'items' => [
                 ['label' => 'Change Password', 'url' => '/oms/site/changepassword'],
                 '<li class="divider"></li>',
                 ['label' => 'Logout', 'url' => '/oms/site/logout', 'linkOptions' => ['data-method' => 'post']],
                  
            ],     
            ],
    ];
      
    }//closing of elseif
    
    else if  (strtolower(Yii::$app->session['Role'])== 'hr'){
        $menuItems = [
        ['label' => 'MyInfo', 'url' => ['/user/myinfo/myinfo']],
        ['label' => 'Attendance', 'url' => ['/attendance/attendance/index']],
        ['label' => 'Employee', 'url' => ['/user/employee/index']],
        ['label' => 'Leave', 'url' => ['/leave/leave/index']],
        ['label' => 'DailyReport', 'url' => ['/dailyreport/dailyreport/index']],
        ['label' => Yii::$app->session['FullName'],
               'items' => [
                 ['label' => 'Change Password', 'url' => '/oms/site/changepassword'],
                 '<li class="divider"></li>',
                 ['label' => 'Logout', 'url' => '/oms/site/logout', 'linkOptions' => ['data-method' => 'post']],
                  
            ],     
            ],
    ];
      
    }
    else{
        $menuItems = [
           ['label' => 'MyInfo', 'url' => ['/user/myinfo/myinfo']],
           ['label' => 'Attendance', 'url' => ['/attendance/attendance/index']],
           ['label' => 'Leave', 'url' => ['/leave/leave/index']],
           ['label' => 'DailyReport', 'url' => ['/dailyreport/dailyreport/index']],

           ['label' => Yii::$app->session['FullName'],
               'items' => [
                 ['label' => 'Change Password', 'url' => '/oms/site/changepassword'],
                 '<li class="divider"></li>',
                 ['label' => 'Logout', 'url' => '/oms/site/logout', 'linkOptions' => ['data-method' => 'post']],
                  
            ],     
            ],
    ];
        
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
