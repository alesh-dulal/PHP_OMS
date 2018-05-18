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
        'options' => ['placeholder' => '- Select Name -'],
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
     <div class="select"><select class="custom-control" id="advance-rule" name="Advance[Rule]">
      <option value="0">Deduct Once</option>
      <option value="1">Deduct Monthly</option>
    </select><div class="select_arrow"></div></div>
    </div>
    

    <?= $form->field($model, 'Month')->textInput()?>

    <div id="decuctList">
        <ul class="list-group">
            <li class="list-group-item"></li>
        </ul>
    </div>

    <div class="form-group form-btn" id="buttons">
        <?= Html::button('Reset', ['class' => 'btn btn-default advance-reset', 'value'=>'reset']) ?>
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

			      </tr>
			    </thead>
			    <tbody>
                    <?php if(sizeof($advances) == 0){?><tr><td colspan="2" align="center">No Data Available</td></tr>
                    <?php }else{
                    foreach ($advances as $key => $advance):?>
				<tr>
					<td emp-id = "<?=$advance['EmployeeID']?>"><?= $advance['Name'] ?></td>
                    <td><?= $advance['Amount'] ?></td>

				</tr>
			<?php  endforeach; }?>
			    </tbody>
			  </table>
		</div>
	</div>
</div>

<?php 
$js = <<< JS

$(document).on({
    ajaxStart: function() { nowLoading(); $("body").addClass("loading");    },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});
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
                    buffer+="<li>"+data[index]+"-"+monthlyDeduction+"</li>";
                });

                $('div#decuctList').find('ul').html(buffer);
                $('div#decuctList').show();
            },

            error:function(){
                showError("List Not Available. Server Error.");
            }
        });
    }

    //ajax for save and update data 

    $('div.form-group').find('button.advance-save').on('click',function(){
        var ele = $('div.form-group');
        var advanceArray = new Array();
        var rule = ele.find('select[name="Advance[Rule]"]').val();
        $('div#decuctList').find('ul.list-group > li').each(function(){
            var name = ele.find('select[name="Advance[EmployeeID]"]').val();
            var amount = ele.find('input[name="Advance[Amount]"]').val();
            var advanceOf = $(this).text();
            advanceArray.push({"EmployeeID":name,"Amount":amount, "AdvanceOf":advanceOf});
            });
        SaveData(rule, advanceArray);
    });
    
    function SaveData(rule, advanceArray, callMe) {
        $.ajax({
            type: "POST",
            url: "advance/savedata",
            data: {
                "rule": rule,
                "advanceArray": advanceArray
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
$('div#buttons').find('button.advance-reset').on('click', function(){
    $('div#buttons').find('button.advance-save').attr('data-id',0);
    resetFields();
    $('div#decuctList').find('ul.list-group li').remove();
     $('div.field-advance-month').hide();
});

function resetFields(){
    var inputArray = document.querySelectorAll('input');
    inputArray.forEach(function (input){
        input.value = "";
    });
    $('select').val("0").trigger('change');
}
JS;

$this->registerJS($js);
?>

<?php  
$this->registerCSS("
        .form-btn{
        float:right;
    }

    // select::-ms-expand { display: block; }


    .select {
  position: relative;
  display: inline-block;
  margin-bottom: 15px;
  width: 100%;
}

.select select.custom-control {
  font-family: 'Arial';
  display: inline-block;
  width: 100%;
  cursor: pointer;
  padding: 6px 12px;
  outline: 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  background: #fff;
  color: #555;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
  -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

.select select.custom-control::-ms-expand {
  display: none;
}

.select select.custom-control:hover,
.select select:focus {
  color: #555;
  background: #fff;
}

.select select.custom-control:disabled {
  opacity: 0.5;
  pointer-events: none;
}

.select_arrow {
  position: absolute;
  top: 11px;
  right: 12px;
  pointer-events: none;
  border-style: solid;
  border-width: 8px 5px 0px 5px;
  border-color: #555 transparent transparent transparent;
}

.select select.custom-control:hover~.select_arrow,
.select select.custom-control:focus~.select_arrow {
  border-top-color: #555;
}

.select select.custom-control:disabled~.select_arrow {
  border-top-color: #555;
}



    ");
 ?>