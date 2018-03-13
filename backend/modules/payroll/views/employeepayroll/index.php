<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
?>

	<div class="container" id="containerPayroll">

	<div class="row">
	   <div class="col-lg-12">
	      <div class="col-lg-6 well create-employee-payroll">
	         <?php $form = ActiveForm::begin(); ?>
	         <div class="form-group">
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
	         </div>

	         <div class="form-group basic-salary">
	            <label for="employeepayroll-basicsalary">Basic Salary: Rs.</label>
	            <span id="employeepayroll-basicsalary">0.00</span>
	         </div>

	         <!-- start of div assigned -->
	         <div class="row assigned">
	         	<div class="col-lg-12">
	         		<div class="col-lg-6 allowanceAssigned">
	         			<h5  align="center">Allowance</h5>
						<!-- start of tableAllowanceAssigned -->
						<table id="tableAllowanceAssigned" class="table table-bordered">
	                     <thead>
	                        <tr>
	                           <th>Allowance</th>
	                           <th>Amount</th>
	                        </tr>
	                     </thead>
	                     <tbody>
	                          <!-- Allowances will  be assigned here -->
	                     </tbody>
	                  </table>
	         			<!-- end of tableAllowanceAssigned -->
	         		</div>
	         		<div class="col-lg-6 deductionAssigned">
	         			<h5  align="center">Deductions</h5>
	         			<!-- start of tableDeductionAssigned -->
	         			<table id="tableDeductionAssigned" class="table table-bordered">
	                     <thead>
	                        <tr>
	                           <th>Allowance</th>
	                           <th>Amount</th>
	                        </tr>
	                     </thead>
	                     <tbody>
	                          <!-- Deductions will  be assigned here -->
	                     </tbody>
	                  </table>
	         			<!-- end of tableDeductionAssigned -->
	         		</div>
	         	</div>
	         </div>
	         <!-- end of div assigned -->
	         <div class="row total-values">
	         	<div class="col-lg-12">
	         		<div class="col-lg-6 totalAllowance">
	         			<p><span>Allowance Total: Rs.</span><span class="total-allowance-value">0.00</span></p>
	         		</div>
	         		<div class="col-lg-6 totalDeduction">
	         			<p><span>Deduction Total: Rs.</span><span class="total-deduction-value">0.00</span></p>
	         		</div>
	         	</div>
	         </div>

	         <div class="add-all">
	         	<button id="btnAddAll" class="btn btn-primary glyphicon glyphicon-plus" type="button">Add All</button>
	         </div>

	         <!-- start of div unassigned -->
	         <div class="row unassigned">
	         	<div class="col-lg-12">
	         		<div class="col-lg-6 allowanceUnassigned">
						<!-- start of tableAllowanceAssigned -->
						<table id="tableAllowanceUnassigned" class="table table-bordered">
	                     <thead>
	                        <tr>
	                           <th>Allowance</th>
	                           <th>Amount</th>
	                           <th hidden="true">Formula</th>
	                        </tr>
	                     </thead>
	                     <tbody>
	                          <!-- Allowances will  be populated here -->
	                     </tbody>
	                  </table>
	         			<!-- end of tableAllowanceAssigned -->
	         		</div>
	         		<div class="col-lg-6 deductionUnassigned">
	         			<!-- start of tableDeductionUnassigned -->
	         			<table id="tableDeductionUnassigned" class="table table-bordered">
	                     <thead>
	                        <tr>
	                           <th>Allowance</th>
	                           <th>Amount</th>
	                           <th hidden="true">Formula</th>
	                        </tr>
	                     </thead>
	                     <tbody>
	                          <!-- Deductions will  be populated here -->
	                     </tbody>
	                  </table>
	         			<!-- end of tableDeductionUnassigned -->
	         		</div>
	         	</div>
	         </div>
	         <!-- end of div unassigned -->
	         <div class="form-group">
	            <label for="employeepayroll-totalsalary">Total Salary: Rs.</label>
	            <span id="employeepayroll-totalsalary">0.00</span>
	         </div>
	         <div class="form-group">
	            <?= Html::button('Save', ['class' => 'btn btn-primary employee-payroll-save', 'value'=>'save','data-id'=>'0']) ?>
	         </div>
	         <?php ActiveForm::end(); ?>
	      </div>
	   </div>
	</div>
</div>


<?php 
$js = <<< JS
$('document').ready(function() {
	CheckBasicSalary();
	AllowanceList()
});
function AllowanceList(){
	$.ajax({
        type: "POST",
        url: "employeepayroll/allowancelist",
        data: {

        },
        dataType: 'json',
        cache: false,
        success: function(data) {
        	//showMessage("Retrieved Successfully.");
        	var ele = $('div.unassigned')
        	ele.find('table#tableAllowanceUnassigned tbody').append(data['allowance']);
        	ele.find('table#tableDeductionUnassigned tbody').append(data['deduction']);
        },
        error: function(data) {
            showError("Server Error. Allowance List are not retrieved.");
        }
    });
}

$('div.form-group').find('select#employeepayroll-employeeid').change(function() {
	var elt = $('div#containerPayroll');
	elt.find('div.totalAllowance span.total-allowance-value').text("0.00");
	elt.find('div.totalDeduction span.total-deduction-value').text("0.00");
	elt.find('span#employeepayroll-totalsalary').text("0.00");

	$('div#containerPayroll').find('div.assigned table tbody tr').remove();
	$('div#containerPayroll').find('div.unassigned table tbody tr').remove();
	AllowanceList();

	var ele = $('div.form-group');
    var employeeID = ele.find('select#employeepayroll-employeeid').val();
    RetrieveInfo(employeeID);
});

function RetrieveInfo(employeeID) {
    $.ajax({
        type: "POST",
        url: "employeepayroll/empsalary",
        data: {
            "employeeID": employeeID
        },
        dataType: 'json',
        cache: false,
        success: function(data) {

            showMessage("BS Retrieved Successfully");
            var ele = $('div.form-group');
            ele.find('span#employeepayroll-basicsalary').text(data['BasicSalary']);
            CheckBasicSalary();
        },
        error: function() {
            showError("Server Error.");
        }
    });
}

$('div#containerPayroll').find('div.add-all button').on('click', function(){
	AssignAllRows();
	$(this).prop('disabled', true);
});
//function for enabling and disabling the add all button
function CheckBasicSalary(){
	var BasicSalary = $('div#containerPayroll').find('div.basic-salary span').text();
	if(BasicSalary != parseInt(0)){
		$('div#containerPayroll').find('div.add-all button').removeAttr("disabled");
	}else{
		$('div#containerPayroll').find('div.add-all button').attr('disabled', 'disabled');
	}
}

function AssignAllRows(){
	var rowAllowance = $('div.unassigned table#tableAllowanceUnassigned tbody').html();
	$('div.unassigned table#tableAllowanceUnassigned tbody > tr').remove();
	$('div.assigned').find('table#tableAllowanceAssigned tbody').append(rowAllowance);
	$('div.assigned').find('table#tableAllowanceAssigned tbody tr td:nth-child(2)').append('<span class="close remove-this" aria-label="Close">&times;<span>');

	var rowDeduction = $('div.unassigned table#tableDeductionUnassigned tbody').html();
	$('div.unassigned table#tableDeductionUnassigned tbody > tr').remove();
	$('div.assigned').find('table#tableDeductionAssigned tbody').append(rowDeduction);
	$('div.assigned').find('table#tableDeductionAssigned tbody tr td:nth-child(2)').append('<span class="close remove-this" aria-label="Close">&times;<span>');

Calculation();
CalculateTotal();
totalSalary();
}

//remove the unassigned tr
$('div#containerPayroll').find('div.assigned table#tableAllowanceAssigned').on('click', 'span.remove-this', function(e){
    var tr = $(this).parents('tr');
    $('div.unassigned table#tableAllowanceUnassigned tbody').append(tr).find("span").remove();
    $('div.unassigned table#tableAllowanceUnassigned tbody tr td:nth-child(2)').append('<span class="close add-this" aria-label="Close">&#10003;<span>');
    CalculateTotal();
    totalSalary();
});

$('div#containerPayroll').find('div.assigned table#tableDeductionAssigned').on('click', 'span.remove-this', function(e){
        var tr = $(this).parents('tr');
        $(this).parents('tr').remove();
        $('div.unassigned table#tableDeductionUnassigned tbody').append(tr).find("span").remove();
        $('div.unassigned table#tableDeductionUnassigned tbody tr td:nth-child(2)').append('<span class="close add-this" aria-label="Close">&#10003;<span>');
        CalculateTotal();
        totalSalary();
});

$('div#containerPayroll').find('div.unassigned table#tableAllowanceUnassigned').on('click', 'span.add-this', function(e){
    var tr = $(this).parents('tr');
    $(this).parents('tr').remove();
    $('div.assigned table#tableAllowanceAssigned tbody').append(tr).find("span").remove();
    $('div.assigned table#tableAllowanceAssigned tbody tr td:nth-child(2)').append('<span class="close remove-this" aria-label="Close">&times;<span>');
    CalculateTotal();
    totalSalary();
});

$('div#containerPayroll').find('div.unassigned table#tableDeductionUnassigned').on('click', 'span.add-this', function(e){
        var tr = $(this).parents('tr');
        $(this).parents('tr').remove();
        $('div.assigned table#tableDeductionAssigned tbody').append(tr).find("span").remove();
        $('div.assigned table#tableDeductionAssigned tbody tr td:nth-child(2)').append('<span class="close remove-this" aria-label="Close">&times;<span>');
        CalculateTotal();
        totalSalary();
});

//ajax for calculating
$('div#containerPayroll').find('div.form-group button.employee-payroll-save').on('click', function() {
	var AllowanceID = new Array();
	 var ele = $('div#containerPayroll');
	 var employeeID = ele.find('select#employeepayroll-employeeid').val();
	 $('div#containerPayroll').find('div.assigned table tbody tr').each(function(){
	 	var val1 = $(this).attr('data-type');
	 	var val2 = $(this).attr('data-id');
	 	var val3 = $(this).find('td:eq(0)').text();
	 	var val4 = parseFloat($(this).find('td:eq(1)').text());
	 	val4 = val4 || 0;
	 	AllowanceID.push({"Type":val1, "ID":val2, "Name":val3, "Value":val4});
	 })
	 console.log(AllowanceID);
	 SaveCalc(employeeID, AllowanceID);
});

function SaveCalc(employeeID, AllowanceID) {
 $.ajax({
	  type: "POST",
	  url: "employeepayroll/calculate",
	  data: {
	   "employeeID": employeeID,
	   "AllowanceID": AllowanceID
	  },
	  dataType: 'json',
	  cache: false,
	  success: function(data) {
		   if(data.result == true){
		   showMessage(data.message);
		   location.reload();
		   }else{
		   	showError(data.message);
		   }
	  },
	  error: function() {
	   	showError("Server Error.");
	  }
 });
}

function Calculation(){
	var ele = $('div#containerPayroll')
	var BasicSalary = ele.find('div.basic-salary span').text();
	ele.find('div.assigned table tbody tr').each(function(){
		var formulae = $(this).find('td:eq(2)').text();
		if(formulae != ""){
			var formRep = formulae.replace('BS',BasicSalary);
			$(this).find('td:eq(1)').text(eval(formRep).toFixed(2)).append('<span class="close remove-this" aria-label="Close">&times;<span>');

		}
	});
}
function CalculateTotal()
{
	var SumAllowance = 0;
	var SumDeduction = 0;
	var AllowanceValue = 0;
	var DeductionValue = 0;
	var ele = $('div#containerPayroll');

	ele.find('div.assigned table').each(function()
	{
		if($(this).parents('div').hasClass('allowanceAssigned'))
		{
			ele.find('div.allowanceAssigned table#tableAllowanceAssigned tbody tr').each(function()
			{
				AllowanceValue = parseFloat($(this).children('td:eq(1)').text());
				AllowanceValue = AllowanceValue || 0;
				SumAllowance +=  AllowanceValue;
			});
		}
		else
		{
			ele.find('div.deductionAssigned table#tableDeductionAssigned tbody tr').each(function()
			{
				DeductionValue = parseFloat($(this).children('td:eq(1)').text());
				DeductionValue = DeductionValue || 0;
				SumDeduction +=  DeductionValue;
			});
		}
	});
	ele.find('div.totalAllowance span.total-allowance-value').text(SumAllowance);
	ele.find('div.totalDeduction span.total-deduction-value').text(SumDeduction);
}
function totalSalary(){
	var totalSalary = 0 ;
	var ele = $('div#containerPayroll');
	var basicSalary = parseFloat($('div#containerPayroll').find('div.basic-salary span').text());
	var totalAllow = parseFloat(ele.find('div.totalAllowance span.total-allowance-value').text());
	var totalDeduc = parseFloat(ele.find('div.totalDeduction span.total-deduction-value').text());
	totalSalary = eval((basicSalary + totalAllow) - totalDeduc);
	ele.find('span#employeepayroll-totalsalary').text(totalSalary);
}

 $('div#containerPayroll').find('div.assigned table').on('input', 'td[contenteditable]', function() {
 	CalculateTotal();
 	totalSalary();
});

JS;

$this->registerJS($js);
?>

<?php
	$this->registerCss("
	.typeName label {
		display: block;
    	text-align:center;
    	line-height:150%;
    	font-size:1.5em;
	}
	.btn{
		float:right;
	}
	.icon-button {
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
		outline: none;
		border: 0;
		background: transparent;
	}
	");
?>