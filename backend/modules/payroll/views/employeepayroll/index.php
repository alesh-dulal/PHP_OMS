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
	         	<button id="btnAddAll" class="butt btn btn-primary glyphicon glyphicon-plus" type="button">Add All</button>
	         </div><br><br><br>
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
	         <div class="form-group other-tax">
	            <label for="employeepayroll-otherTax">Other Tax: </label>
	            <input type="text" id="employeepayroll-otherTax">
	         </div>
	         <div class="form-group">
	            <?= Html::button('Save', ['class' => 'btn btn-primary butt employee-payroll-save', 'value'=>'save','data-id'=>'0']) ?>
	         </div>
	         <?php ActiveForm::end(); ?>
	      </div>
	      <div class="col-lg-4 well deny" id="Pdeny">
			<h2>Payroll Deny List</h2>
			<ul class="list-group">
			</ul>
			<button title="Undeny These" id="unDeny" style="display: none;" class="butt-deny un-deny btn btn-default">Undeny</button>
			<button title="Deny More Payroll" id="denyMore" class="butt-deny deny-more btn btn-danger">Deny More</button>
	      </div>
	   </div>
	</div>

<!-- start of modal -->
<div id="payrollDenyModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Deny Payroll</h4>
      </div>
      <div class="modal-body">
        	<table class="" id="denyTable">
        		<thead>
        			<tr>
        			    <td><input type="checkbox" title="Check All" name="headerdeny"  id="checkDenyAll"></td>
        			    <td>Employee Name</td>
        			</tr>
        		</thead>
        		<tbody>
        			
        		</tbody>
        	</table>
      </div>
      <div class="modal-footer">
      	<div class="form-group">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>	
	      	<button type="button" class="btn btn-danger" id="denyPayroll">Deny</button>
      	</div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end of modal -->
<?php 
$js = <<< JS

$(document).on({
    ajaxStart: function() { nowLoading(); $("body").addClass("loading");    },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});

$('document').ready(function() {
	CheckBasicSalary();
	AllowanceList()
	/*deny check all*/
	var tablePay = $('div#payrollDenyModal').find('div.modal-body table#denyTable');
	tablePay.find('input[name="headerdeny"]').on('change', function(){
		var box = tablePay.find('input.deny-this');
		box.each(function(){
			if (tablePay.find('input[name="headerdeny"]').is(':checked')) {
				box.prop('checked',true);
			}else{
				box.prop('checked',false);
			}
		});
	});
	/*end of deny check all*/

	payrollDeniaedlist();
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
	totalSalary = Math.round(eval((basicSalary + totalAllow) - totalDeduc));
	ele.find('span#employeepayroll-totalsalary').text(totalSalary);
}

 $('div#containerPayroll').find('div.assigned table').on('input', 'td[contenteditable]', function() {
 	CalculateTotal();
 	totalSalary();
});


$('div#containerPayroll').find('div.deny button.deny-more').on('click', function(){
	getAllowedEmployeeForPayrollDenial();
	$('div#payrollDenyModal').modal();

});

$('div#containerPayroll').find('div.deny ul').on('click','input.undeny-this', function(){
		 var getClass = $(this).attr('class');
		 var flag = 0;
		 var button = $('div#containerPayroll').find('div.deny').find('button#unDeny');

		$('input[class="' + getClass + '"]').each(function(){
			if($(this).is(':checked'))
				flag = 1;
		});

		if(flag) {
			button.css("display","block");
		} else {
			button.css("display","none");
		}
	});

$('div#containerPayroll').find('div.deny').find('button#unDeny').on('click', function(){
	var arr = $('div#containerPayroll').find('div.deny ul li input[name="undenythis"]:checked').map(function() {
			var obj = {};
				$(this).each(function(i) {
					obj["EmployeeID"] = $(this).attr("attr-empid");
				})
				return obj;
			}).get();
	/*call a function to un-deny these employee's payroll*/
	undenialEmployeePayroll(arr);
});

$('div#payrollDenyModal').find('div.modal-footer button#denyPayroll').on('click', function(){
			var arr = $('div#payrollDenyModal').find('div.modal-body table#denyTable .deny-this:checked').map(function() {
			var obj = {};
				$(this).each(function(i) {
					obj["EmployeeID"] = $(this).attr("attr-id");
				})
				return obj;
			}).get();

	/*call a function to deny these employee's payroll*/
	denialEmployeePayroll(arr);
});


function getAllowedEmployeeForPayrollDenial()
{
	$.ajax({
	  type: "POST",
	  url: "employeepayroll/employeelistfordeny",
	  data: {
	  },
	  dataType: 'json',
	  cache: false,
	  success: function(data) {
	  		$('div#payrollDenyModal').find('div.modal-body table tbody').append(data.message);
	  },
	  error: function() {
	   	showError(data.message);
	  }
 });
}

function denialEmployeePayroll(arr)
{
	$.ajax({
	  type: "POST",
	  url: "employeepayroll/denypayroll",
	  data: {
	   "array": arr,
	  },
	  dataType: 'json',
	  cache: false,
	  success: function(data) {
		   showMessage(data.message);
		   location.reload();
	  },
	  error: function() {
	   	showError(data.message);
	  }
 });
}

function payrollDeniaedlist()
{
	$.ajax({
	  type: "POST",
	  url: "employeepayroll/deniedlist",
	  data: {
	  },
	  dataType: 'json',
	  cache: false,
	  success: function(data) {
		   $('div#Pdeny').find('ul.list-group').append(data.message);
	  },
	  error: function() {
	   	showError(data.message);
	  }
 });
}

function undenialEmployeePayroll(arr)
{
	$.ajax({
	  type: "POST",
	  url: "employeepayroll/undenialpayroll",
	  data: {
	   "array": arr,
	  },
	  dataType: 'json',
	  cache: false,
	  success: function(data) {
		   showMessage(data.message);
		   location.reload();
	  },
	  error: function() {
	   	showError(data.message);
	  }
 });
}

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
.butt{
		float:right;
	}
	.butt-deny{
		float:right;
		margin-right:5px;
	}
	.icon-button {
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
		outline: none;
		border: 0;
		background: transparent;
	}

	.deny{
		margin-left: 20px;
	}
	.list-group-item{
		margin-top:3px;
		padding:4px;
	}
	#denyTable thead tr td{
		padding: 1px;
  		font-size: 15px;
	}
	#denyTable tbody tr td{
		padding: 1px;
  		font-size: 13px;
	}


	#denyTable tbody {
		display:block;
		height:250px;
		overflow:auto;
	}

	#denyTable thead,#denyTable tbody tr {
		display:table;
		width:100%;
		table-layout:fixed;
	}

	#denyTable thead {
		background:#e6e6e6;
		width: calc( 100% - 1em )
	}


	#denyTable tr td:first-child {
    	width: 20px;
	}

	

	");
?>