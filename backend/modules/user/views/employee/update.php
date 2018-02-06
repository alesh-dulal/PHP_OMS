<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employee */

$this->title = 'Update: ' . $model->FullName;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->EmployeeID, 'url' => ['view', 'id' => $model->EmployeeID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="employee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'Department' => $Department,
        'Designation' => $Designation,
        'Role' => $Role,
        'Room' => $Room,
		'Biometric' => $Biometric,
        'Shift' => $Shift,
        'Supervisor' => $Supervisor,
    ]) ?>

</div>
