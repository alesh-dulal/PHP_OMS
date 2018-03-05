<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
 ?>
<h2 align="center">Advance Salary</h2>

<div class="container">
	<div class="col-lg-12 row">
		<div class="col-lg-4 create-advance">
			<h4 align="center">Advance Form</h4>
<!-- start of advance form -->
<div class="advance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    echo $form->field($model, 'EmployeeID')->widget(Select2::classname(), [
            'data' => Yii::$app->empList->listEmployee(),
            'language' => 'en',
            'pluginOptions' => [
                    'allowClear' => true
                 ],
    ])->label('Name');
    ?>
    <?= $form->field($model, 'Amount')->textInput() ?>
    <div class="form-group">
        <label for="advance-rule">Rule</label>
     <select class="form-control" id="advance-rule" name="Advance[Rule]">
      <option value="0">Deduct Once</option>
      <option value="1">Deduct Monthly</option>
    </select> 
    </div>
    

    <?= $form->field($model, 'Month')->textInput()?>

    <div id="decuctList">
        <ul class="list-group">
            <li class="list-group-item"></li>
        </ul>
    </div>

    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-primary advance-save', 'value'=>'save','data-id'=>'0']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<!-- end of advance form -->

		</div>
		<div class="col-lg-8 show-advance">
			<h4 align="center">Advances</h4>
			 <table class="table table-bordered table-responsive">
			    <thead>
			      <tr>
			        <th>Name</th>
			        <th>Amount</th>
			        <th>Rule</th>
			        <th>Month</th>
			        <th>Action</th>
			      </tr>
			    </thead>
			    <tbody>
			<?php foreach ($advances as $key => $advance):?>
				<tr>
					<td emp-id = "<?=$advance['EmployeeID']?>"><?= $advance['Name'] ?></td>
					<td><?= $advance['Amount'] ?></td>
					<td rule-id ="<?=$advance['Rule'] ?>"><?= $advance['Rules'] ?></td>
					<td><?= $advance['Month'] ?></td>
					<td><span class="hand edit" data-id="<?= $advance['AdvanceID'] ?>">edit</span></td>
				</tr>
			<?php endforeach ?>
			    </tbody>
			  </table>
		</div>
	</div>
</div>

<?php 
$js = <<< JS
	$(document).ready(function(){
        $('div.field-advance-month').hide();
    	$('div#decuctList').hide();
	});

	$('select#advance-rule').on('change', function(){
		if($('select#advance-rule option:selected').val() == 1){
    	$('div.field-advance-month').show();
		}else{
    	$('div.field-advance-month').hide();
		}
	});

$('div.form-group').find('input[name="Advance[Month]"]').keyup(function(event){
	var DeductionMonths = $('div.form-group').find('input[name="Advance[Month]"]').val();
	var AdvanceAmount = $('div.form-group').find('input[name="Advance[Amount]"]').val();
    var monthlyDeduction = parseInt(AdvanceAmount)/parseInt(DeductionMonths);
	GetDeduction(DeductionMonths, AdvanceAmount, monthlyDeduction);
});

	function GetDeduction(DeductionMonths, AdvanceAmount, monthlyDeduction) {
        $.ajax({
            type: "POST",
            url: "advance/deductions",
            data: {
                "DeductionMonths": DeductionMonths,
                "AdvanceAmount": AdvanceAmount
            },
            dataType:'json',
            cache: false,
            success: function(data) {
            	var buffer = "";
                $.each(data, function(index){
                    buffer+="<li>"+data[index]+" "+monthlyDeduction+"</li>";
                });

                $('div#decuctList').find('ul').html(buffer);
                $('div#decuctList').show();
            },

            error:function(){
                showError("List Not Available. Server Error.");
            }
        });
    }

    $('div.container table').on('click','span.edit', function(){
		GetSingleRecord($(this));
    });
    function GetSingleRecord(edit){
    	var ele=$('div.advance-form');
    	ele.find('button.advance-save').attr('data-id',edit.attr('data-id'));
    	ele.find('select[name="Advance[EmployeeID]"] ').val(edit.parents('tr').find('td:eq(0)').attr('emp-id')).trigger('change');
    	ele.find('input[name="Advance[Amount]"]').val(edit.parents('tr').find('td:eq(1)').text());
    	ele.find('select[name="Advance[Rule]"] ').val(edit.parents('tr').find('td:eq(2)').attr('rule-id')).trigger('change');
    	ele.find('input[name="Advance[Month]"]').val(edit.parents('tr').find('td:eq(3)').text());
    }

    //ajax for save and update data 

    $('div.form-group').find('button.advance-save').on('click',function(){
        var ele = $('div.form-group');
        var name = ele.find('select[name="Advance[EmployeeID]"]').val();
        var amount = ele.find('input[name="Advance[Amount]"]').val();
        var rule = ele.find('select[name="Advance[Rule]"]').val();
        var month = ele.find('input[name="Advance[Month]"]').val();
        var isNewRecord = ele.find('button.advance-save').attr('data-id');
        SaveData(isNewRecord, name, amount, rule, month);
    });

    function SaveData(isNewRecord, name, amount, rule, month, callMe) {
        $.ajax({
            type: "POST",
            url: "advance/savedata",
            data: {
                "isNewRecord": isNewRecord,
                "name": name,
                "amount": amount,
                "rule": rule,
                "month": month
            },
            dataType:'json',
            cache: false,
            success: function(data) {
                $('div.form-group').find('button.advance-save').attr('data-id',0);
                 location.reload();
                showMessage("Data Saved Successfully.");
            },

            error:function(data){
                showError("Data Not Saved. Please Try Again");
            }
        });
    }

JS;

$this->registerJS($js);
?>
