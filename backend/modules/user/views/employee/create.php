<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employee */

$this->title = 'Create Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

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

<?php 
  $js = <<< JS
  $('document').ready(function(){
    $("#employee-roleid").select2("val", '5');
    $("#employee-departmentid").select2("val", '2');
    $("#employee-designationid").select2("val", '7');
    $("#employee-roomid").val("16").trigger("change");
    $("#employee-shiftid").val("11").trigger("change");

    $("#employee-logintime").val("10:00");
    $("#employee-logouttime").val("17:00");
  });
JS;

$this->registerJS($js);
 ?>