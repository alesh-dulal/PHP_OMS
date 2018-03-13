<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
 ?>
 <h2 align="center">Payroll Setting</h2>

	<div class="container">
	<div class="col-lg-12 row">
	   <div class="col-lg-4 create-payroll-settings">
	      <h4 align="center">Create Settings</h4>
	      <!-- start of payroll-settings form -->
	      <div id="payrollForm" class="payroll-settings-form">
	      	<h1 id="header" align="center"></h1>
	      	<!-- payroll-setting-form -->
	      	<?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'Title')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'Amount')->textInput() ?>
                <?= $form->field($model, 'Formula')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'OrderNo')->textInput(['maxlength' => true]) ?>
                <div class="form-group">
<label class="radio-inline">
  <input type="radio" data-id="Allowance" id="allowance" value="0" class="setting-type" name="Payrollsetting[IsAllowance]" checked>Allowance
</label>
<label class="radio-inline">
  <input type="radio" data-id="Deduction" id="deduction" value="1" class="setting-type" name="Payrollsetting[IsAllowance]">Deduction
</label>
                </div>

  <div class="form-group form-btn" id="buttons">
  <?= Html::button('Reset', ['class' => 'btn btn-default payroll-setting-reset', 'value'=>'reset']) ?>
  <?= Html::button('Save', ['class' => 'btn btn-primary payroll-setting-save', 'value'=>'save','data-id'=>'0']) ?>
  </div>

                <?php ActiveForm::end(); ?>
	      	<!-- payroll-setting-form end-->
	      </div>
	      <!-- end of payroll-settings form -->
	   </div>
	   <div class="col-lg-8 show-payroll-settings">
	      <h4 align="center">Settings</h4>
	      <table class="table table-bordered table-responsive">
	         <thead>
	            <tr>
	               <th>Type</th>
	               <th>Title</th>
                 <th>Amount</th>
	               <th>Order</th>
                 <th>Formula</th>
	               <th>Action</th>
	            </tr>
	         </thead>
	         <tbody>
	            <?php foreach ($allowances as $key => $allowance):?>
	            <tr>
	               <td data-id="<?= $allowance['IsAllowance']?>"><?= $allowance['SettingType'] ?></td>
	               <td><?= $allowance['Title'] ?></td>
                 <td><?= $allowance['Amount'] ?></td>
                 <td><?= $allowance['OrderNo'] ?></td>
                 <td><?= $allowance['Formula'] ?></td>
	               <td><span class="hand edit" data-id="<?= $allowance['PayrollSettingID'] ?>">edit</span></td>
	            </tr>
	            <?php endforeach ?>
	         </tbody>
	      </table>
	   </div>
	</div>
</div>

<?php 
   $this->registerCss("
  
     ");
    ?>

<?php 
$js = <<< JS
$(document).ready(function(){
ShowHeader();
});

$("div#payrollForm").find('input[type="radio"]').on("change", function(){
	ShowHeader();
})

function ShowHeader(){
	var ele = $("div#payrollForm");
	var value = ele.find('input:radio:checked').attr('data-id');
	$("div#payrollForm").find('h1#header').text(value);
}


  $('div.container table').on('click','span.edit', function(){
    GetSingleRecord($(this));
  });

    function GetSingleRecord(edit){
      var ele=$('div.payroll-settings-form');
      ele.find('button.payroll-setting-save').attr('data-id',edit.attr('data-id'));
      var selectedValue=edit.parents('tr').find('td:eq(0)').attr('data-id');
      ele.find('input[value="'+selectedValue+'"]').prop('checked', true);

      ele.find('input[name="Payrollsetting[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
      ele.find('input[name="Payrollsetting[Amount]"]').val(edit.parents('tr').find('td:eq(2)').text());
      ele.find('input[name="Payrollsetting[OrderNo]"]').val(edit.parents('tr').find('td:eq(3)').text());
      ele.find('input[name="Payrollsetting[Formula]"]').val(edit.parents('tr').find('td:eq(4)').text());
    }

     //ajax for save and update data 

    $('div.form-group').find('button.payroll-setting-save').on('click',function(){
        var ele = $('div.form-group');
        var isAllowance = ele.find('input[name="Payrollsetting[IsAllowance]"]:checked').val();
        var title = ele.find('input[name="Payrollsetting[Title]"]').val();
        var amount = ele.find('input[name="Payrollsetting[Amount]"]').val();

        var orderNo = ele.find('input[name="Payrollsetting[OrderNo]"]').val();
        var formula = ele.find('input[name="Payrollsetting[Formula]"]').val();
        var isNewRecord = ele.find('button.payroll-setting-save').attr('data-id');
        SaveData(isNewRecord, isAllowance, title, amount, orderNo, formula);    
      });

    function SaveData(isNewRecord, isAllowance, title, amount, orderNo, formula) {
        $.ajax({
            type: "POST",
            url: "payrollsetting/savedata",
            data: {
                "isNewRecord": isNewRecord,
                "isAllowance": isAllowance,
                "title": title,
                "amount": amount,
                "orderNo": orderNo,
                "formula": formula
            },
            dataType:'json',
            cache: false,
            success: function(data) {
                $('div.form-group').find('button.payroll-setting-save').attr('data-id',0);
                location.reload();
                showMessage("Data Saved Successfully.");
            },

            error:function(){
                showError("Data Not Saved. Please Try Again");
            }
        });
    }


$('div#buttons').find('button.payroll-setting-reset').on('click', function(){
    $('div.payroll-settings-form').find('input:radio[value="0"]').prop('checked', true);
    $('div#buttons').find('button.payroll-setting-save').attr('data-id',0);
    resetFields();
});

function resetFields(){
    var inputArray = document.querySelectorAll('input[type="text"]');
    inputArray.forEach(function(input){
        input.value = "";
    });
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