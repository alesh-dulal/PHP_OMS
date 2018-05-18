<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Payroll */
/* @var $form ActiveForm */
?>
<?php if (Yii::$app->session->hasFlash('terminated')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('terminated') ?>
  </div>
<?php endif; ?>

<div class="index">
  <div id="payAndTerminateContainer">
    <h3 align="center">Pay & Terminate
      </h4>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-3">
      <div class="form-group">
        <?php echo $form->field($model, 'EmployeeID')->widget(Select2::classname(), [
'data' => Yii::$app->empList->listEmployee(),
'language' => 'en',
'options' => ['placeholder' => 'Select Employee  ...'],
'pluginOptions' => [
'allowClear' => true
],
])->label("Employee Name"); ?>
      </div>
    </div>
    <div class="form-group col-lg-3">
        <label for="month">Month
        </label>
        <?php echo months(); ?>
    </div>
    <div class="form-group col-lg-3">
        <label for="year">Year
        </label>
        <?php echo years(); ?>
    </div> 
    <div class="form-group col-lg-3">
        <?= $form->field($model, 'BasicSalary') ?>
    </div>
    <div class="fields-allowance"> 
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <?= $form->field($model, 'TotalAllowance')->textInput(['value'=>0]) ?>
      </div>
    </div>
    <div class="fields-deduction">
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'TotalDeduction')->textInput(['value'=>0]) ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'Income') ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'AbsentDays') ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'AbsentDeduction') ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'GrossIncome') ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'SST')->label("SST") ?>
    </div>
    <div class="col-lg-3">
      <div class="other-tax">
        <?= $form->field($model, 'OtherTAX')->textInput(['value'=>0]) ?>
      </div>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'NetIncome')->textInput(['value'=>0]) ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'AdvanceDeduction')->textInput(['value'=>0]) ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'PayableAmount') ?>
    </div>
    <div class="col-lg-3">
      <?= $form->field($model, 'Remarks')->textarea(['rows' => '1']) ?>
    </div>
    <div class="col-lg-12">
      <div class="form-group text-right">
        <?= Html::button('Reset', ['class' => 'btn btn-default btn-md reset', 'value'=>'reset']) ?>
        <?= Html::button('Verify', ['class' => 'btn btn-primary btn-md verify', 'value'=>'verify']) ?>
        <?= Html::submitButton('Pay', ['class' => 'btn btn-danger btn-md']) ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>
<!-- start of modal -->
<div id="payTerminateModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Verify Payment</h4>
      </div>
      <div class="modal-body">
          
      </div>
      <div class="modal-footer">
        <div class="form-group">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end of modal -->


<?php 
$js = <<< JS
var ele = $('div#payAndTerminateContainer');

$('div#payAndTerminateContainer').find('button.verify').on('click', function(){
  var employeeID = ele.find('div.form-group select[name="Payroll[EmployeeID]"] option:selected').val();
  (employeeID != 0)?$('div#payTerminateModal').modal():showMessage("Select An Employee.");
  GetListOnModalBody();
});

function GetListOnModalBody(){
console.log("Cool");
}
RetrieveFields();
ele.find('div.form-group select[name="Payroll[EmployeeID]"]').on('change', function () {
  var employeeID = $(this).find('option:selected').val();
  RetrieveInfo(employeeID);
});

function RetrieveInfo(employeeID) {
  $.ajax({
    type: "POST",
    url: "payandterminate/empsalary",
    data: {
      "employeeID": employeeID
    },
    dataType: 'json',
    cache: false,
    success: function (data) {
      var json = $.parseJSON(data);
      if (json.result == true) {
        ele.find('div.form-group input[name="Payroll[BasicSalary]"]').val(json.bs);
        calculateIncome();
        CalculateAbsentDeduction(employeeID);
        GetAdvance(employeeID);
      }
      if (json.result == "zero") {
        ele.find('div.form-group input[name="Payroll[BasicSalary]"]').val("");
        calculateIncome();
      }
      showMessage(json.message);
    },
    error: function () {
      showError("Server Error.");
    }
  });
}
$(document).on({
    ajaxStart: function() { nowLoading(); $("body").addClass("loading");    },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});

var amountArray = new Array();

ele.find('div.fields-allowance').on('input', 'input.calculate', function () {
  var employeeID = ele.find('div.form-group select[name="Payroll[EmployeeID]"] option:selected').val();
  (employeeID == 0)?showMessage("Select An Employee."):
  ele.find('div.fields-allowance input.calculate').each(function () {
    var amount = $(this).val() || 0;
    amountArray.push(amount);
  });
  var Sum = CalculateSum(amountArray);
  ele.find('div.form-group input[name="Payroll[TotalAllowance]"]').val(Sum)
  calculateIncome();
  calculateGross();
  calculateSST();
  calculateNetIncome();
  CalculatePayableAmount();
  amountArray = [];
  ;
});

ele.find('div.fields-deduction').on('input', 'input.calculate', function () {
  var employeeID = ele.find('div.form-group select[name="Payroll[EmployeeID]"] option:selected').val();
  (employeeID == 0)?showMessage("Select An Employee."):
  ele.find('div.fields-deduction input.calculate').each(function () {
    var amount = $(this).val() || 0;
    amountArray.push(amount);
  });

  var Sum = CalculateSum(amountArray);
  ele.find('div.form-group input[name="Payroll[TotalDeduction]"]').val(Sum)
  calculateIncome();
  calculateGross();
  calculateSST();
  calculateNetIncome();
  CalculatePayableAmount();
  amountArray = [];
    ;
});

ele.find('div.other-tax').on('input', 'input#payroll-othertax', function () {
  calculateNetIncome();
  CalculatePayableAmount();

});

function CalculateSum(array) {
  var total = 0;
  for (var i = 0; i < array.length; i++) {
    total += array[i] << 0;
  }
  return total;
}

function calculateIncome() {
  var BS = parseInt(ele.find('div.form-group input[name="Payroll[BasicSalary]"]').val()) || 0;
  var TotalAllowance = parseInt(ele.find('div.form-group input[name="Payroll[TotalAllowance]"]').val()) || 0;
  var TotalDeduction = parseInt(ele.find('div.form-group input[name="Payroll[TotalDeduction]"]').val()) || 0;
  var income = eval("BS + TotalAllowance - TotalDeduction");
  ele.find('div.form-group input[name="Payroll[Income]"]').val(parseInt(income));

}

function calculateGross() {
  var income = ele.find('div.form-group input[name="Payroll[Income]"]').val();
  var inc = parseInt(income);
  var AbsDeduct = ele.find('div.form-group input[name="Payroll[AbsentDeduction]"]').val();
  var ab = parseInt(AbsDeduct);
  var Gross = eval("income - AbsDeduct");
  ele.find('div.form-group input[name="Payroll[GrossIncome]"]').val(parseInt(Gross));
}

function CalculateAbsentDeduction(id) {
  $.ajax({
    type: "POST",
    url: "payandterminate/employeeabsentdays",
    data: {
      ID: id
    },
    dataType: 'json',
    cache: false,
    success: function (data) {
      ele.find('div.form-group input[name="Payroll[AbsentDays]"]').val(data.AbsDays);
      ele.find('div.form-group input[name="Payroll[AbsentDeduction]"]').val(parseInt(data.AbsDeduct));

      calculateGross();
      calculateSST();
      calculateNetIncome();
    },
    error: function () {
      showError("Server Error.");
    }
  });
}

function calculateSST() {
  var GrossIncome = ele.find('div.form-group input[name="Payroll[GrossIncome]"]').val();
  var SST = eval((1 / 100) * GrossIncome);
  ele.find('div.form-group input[name="Payroll[SST]"]').val(SST.toFixed(2));
}

function calculateNetIncome() {
  var GrossIncome = parseInt(ele.find('div.form-group input[name="Payroll[GrossIncome]"]').val());
  var SST = parseInt(ele.find('div.form-group input[name="Payroll[SST]"]').val());
  var OtherTAX = parseInt(ele.find('div.form-group input[name="Payroll[OtherTAX]"]').val()) || 0;
  var NetIncome = eval("GrossIncome-OtherTAX-SST");
  ele.find('div.form-group input[name="Payroll[NetIncome]"]').val(NetIncome);
}

function CalculatePayableAmount() {
  var NetIncome = parseInt(ele.find('div.form-group input[name="Payroll[NetIncome]"]').val());
  var Advance = parseInt(ele.find('div.form-group input[name="Payroll[AdvanceDeduction]"]').val());
  var PayableIncome = eval("NetIncome-Advance");
  console.log(PayableIncome);
  ele.find('div.form-group input[name="Payroll[PayableAmount]"]').val(PayableIncome);
}

function RetrieveFields() {
  $.ajax({
    type: "POST",
    url: "payandterminate/getfields",
    data: {},
    dataType: 'json',
    cache: false,
    success: function (data) {
      ele.find('div.fields-allowance').append(data.htmlAllowance);
      ele.find('div.fields-deduction').append(data.htmlDeduction);
    },
    error: function () {
      showError("Server Error.");
    }
  });
}

function GetAdvance(id) {
  $.ajax({
    type: "POST",
    url: "payandterminate/employeadvance",
    data: {
      "ID": id
    },
    dataType: 'json',
    cache: false,
    success: function (data) {
      ele.find('input[name="Payroll[AdvanceDeduction]"]').val(data || 0);
      CalculatePayableAmount();
    },
    error: function () {
      showError("Server Error.");
    }
  });
}


JS;

$this->registerJS($js);
?>


<?php  
$this->registerCSS("


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
<?php

function months($selctedMonth = 'january')
{
  $months = '<div class="select"><select name="Payroll[Month]" class="form-select custom-control month" id="payroll-month" size="1">';
  $months.= '<option value="0" disabled>--Select Month--</option>';
  for ($i = 12; $i > 0; $i--)
  {
    $time = strtotime(sprintf('-%d months', $i));
    $label = date('F', $time);
    $selctedM = strtolower($selctedMonth) == strtolower($i) ? 'selected' : '';
    $months.= "<option value='" . date("n", strtotime($label)) . "'  $selctedM >$label</option>";
  }

  $months.= '</select><div class="select_arrow"></div></div>';
  return $months;
}

function years()
{
  $starting_year = date('Y', strtotime('-3 year'));
  $ending_year = date('Y', strtotime('+5 year'));
  $years = '<div class="select"><select class="form-select custom-control id="payroll-year" name="Payroll[Year]" size="1">';
  $years.= '<option value="0" disabled>--Select Year--</option>';
  for ($starting_year; $starting_year <= $ending_year; $starting_year++)
  {
    if (date('Y') == $starting_year)
    {
      $selected = 'selected';
    }
    else
    {
      $selected = '';
    }

    $years.= '<option ' . $selected . ' value="' . $starting_year . '">' . $starting_year . '</option>';
  }

  $years.= '</select><div class="select_arrow"></div></div>';
  return $years;
}


?>

