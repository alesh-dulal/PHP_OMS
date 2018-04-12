<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
?>      

<?php 
 ?>
<h2 align="center">Designation Salary</h2>

<div class="container">
	<div class="col-lg-12 row">
		<div class="col-lg-4 create-designation-salary">
			<h4 align="center">Designation Salary Form</h4>
<!-- start of designation salary form form -->
<div class="designation-salary-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    echo $form->field($model, 'DesignationID')->widget(Select2::classname(), [
        'options' => ['placeholder' => '- Select Designation -'],
            'data' => Yii::$app->empList->designationList(),
            'language' => 'en',
            'pluginOptions' => [
                    'allowClear' => true
                 ],
    ])->label('Designation');
    ?>

    <?= $form->field($model, 'SalaryAmount')->textInput() ?>

<!-- yaa baki xa -->

<div class="form-group form-btn" id="buttons">
        <?= Html::button('Reset', ['class' => 'btn btn-default designation-salary-reset', 'value'=>'reset']) ?>
        <?= Html::button('Save', ['class' => 'btn btn-primary designation-salary-save', 'value'=>'save','data-id'=>'0']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<!-- end of designatinsalary form -->

		</div>
		<div class="col-lg-8 show-designation-salary">
			<h4 align="center">Designation Salary</h4>
			 <table class="table table-bordered table-responsive">
			    <thead>
			      <tr>
			        <th>Designation Name</th>
			        <th>Salary Amount</th>
			        <th>Action</th>
			      </tr>
			    </thead>
			    <tbody>
			<?php foreach ($designationSalaries as $key => $designationSalary):?>
				<tr>
					<td desi-id ="<?=$designationSalary['DesignationID']?>"><?= $designationSalary['Title'] ?></td>
					<td><?= $designationSalary['SalaryAmount'] ?></td>
					<td><span class="hand edit" data-id="<?= $designationSalary['DesignationSalaryID'] ?>">edit</span></td>
				</tr>
			<?php endforeach ?>
			    </tbody>
			  </table>
		</div>
	</div>
</div>


<?php 
$js = <<< JS
    $('div.container table').on('click','span.edit', function(){
		GetSingleRecord($(this));
    });

    function GetSingleRecord(edit){
    	var ele=$('div.designation-salary-form');
    	ele.find('button.designation-salary-save').attr('data-id',edit.attr('data-id'));
    	ele.find('select[name="Designationsalary[DesignationID]"]').val(edit.parents('tr').find('td:eq(0)').attr('desi-id')).trigger('change');
    	ele.find('input[name="Designationsalary[SalaryAmount]"]').val(edit.parents('tr').find('td:eq(1)').text());
    }

     //ajax for save and update data 

    $('div.form-group').find('button.designation-salary-save').on('click',function(){
        var ele = $('div.form-group');
        var designationName = ele.find('select[name="Designationsalary[DesignationID]"]').val();
        var salaryAmount = ele.find('input[name="Designationsalary[SalaryAmount]"]').val();
        var isNewRecord = ele.find('button.designation-salary-save').attr('data-id');
        SaveData(isNewRecord, designationName, salaryAmount);
    });

    function SaveData(isNewRecord, designationName, salaryAmount, callMe) {
        $.ajax({
            type: "POST",
            url: "designationsalary/savedata",
            data: {
                "isNewRecord": isNewRecord,
                "designationName": designationName,
                "salaryAmount": salaryAmount
            },
            dataType:'json',
            cache: false,
            success: function(data) {
                $('div.form-group').find('button.designation-salary-save').attr('data-id',0);
                location.reload();
                showMessage("Saved Successfully.");
                callMe(data);
            },

            error:function(){
                showError("Data Not Saved. Please Try Again");
            }
        });
    }

$('div#buttons').find('button.designation-salary-reset').on('click', function(){
    $('div#buttons').find('button.designation-salary-save').attr('data-id',0);
    resetFields();
});

function resetFields(){
    var inputArray = document.querySelectorAll('input');
    inputArray.forEach(function (input){
        input.value = "";
    });
    $('select').val("").trigger('change');
}
JS;

$this->registerJS($js);
?>

<?php  
$this->registerCSS("
        .form-btn{
        float:right;
    }
    ");
 ?>