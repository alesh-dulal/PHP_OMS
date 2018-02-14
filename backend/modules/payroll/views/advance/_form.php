<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\user\models\Employee;

 use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Advance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    echo $form->field($model, 'EmployeeID')->widget(Select2::classname(), [
            'data' => $EmpList,
            'language' => 'en',
            'pluginOptions' => [
                    'allowClear' => true
                 ],
    ])->label('Name');
    ?>

    <?= $form->field($model, 'Amount')->textInput() ?>

	 <select id="advance-rule" name="Advance[Rule]">
	  <option value="0">Deduct Once</option>
	  <option value="1">Deduct Monthly</option>
	</select> 

    <?= $form->field($model, 'Month')->textInput()?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$js = <<< JS
	$(document).ready(function(){
    	$('div.field-advance-month').hide();
	});

	$('select#advance-rule').on('change', function(){
		if($('select#advance-rule option:selected').val() == 1){
    	$('div.field-advance-month').show();
		}else{
    	$('div.field-advance-month').hide();
		}
	});

	

	$('div.form-group').find('input[name="Advance[Month]"]').keyup(function(event){
	var valMonth = $('div.form-group').find('input[name="Advance[Month]"]').val();
	var Advance = $('div.form-group').find('input[name="Advance[Month]"]').val();

		var month = new Array();
	    month[1] = "January";
	    month[2] = "February";
	    month[3] = "March";
	    month[4] = "April";
	    month[5] = "May";
	    month[6] = "June";
	    month[7] = "July";
	    month[8] = "August";
	    month[9] = "September";
	    month[10] = "October";
	    month[11] = "November";
	    month[12] = "December";

	   var d = new Date();
	   
	});


    



	
JS;

$this->registerJS($js);
?>
