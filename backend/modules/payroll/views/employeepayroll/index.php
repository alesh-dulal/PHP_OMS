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

	         <div class="form-group">
	            <label for="employeepayroll-basicsalary">Basic Salary: </label>
	            <span id="employeepayroll-basicsalary"></span>
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
	            <label for="employeepayroll-totalsalary">Total Salary</label>
	            <span id="employeepayroll-totalsalary">9500</span>
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

$('document').ready(function(){
	$.ajax({
		type:"POST",
		url:"employeepayroll/allowancelist",
		data:{

		},
		dataType: 'json',
		cache: false,
		success: function(data) {
			var ele = $('div.unassigned');
			$.each(data,function(key,value){
				if(value.IsAllowance == 0){
					ele.find('table#tableAllowanceUnassigned tbody').append("<tr><td dataid = "+value.Formula+">" + value.Title + "</td><td contenteditable='true'>"+ value.Amount +"</td></tr>");
				}else{
					ele.find('table#tableDeductionUnassigned tbody').append("<tr><td dataid = "+value.Formula+">" + value.Title + "</td><td contenteditable='true'>"+ value.Amount +"</td></tr>");				
				}
			});
		},
		error: function(data) {
			showError("Server Error. Allowance List are not retrieved.");
		  }
	});
});

$('div.form-group').find('select#employeepayroll-employeeid').change(function() {
	 var ele = $('div.form-group');
	 var employeeID = ele.find('select#employeepayroll-employeeid').val();
	 RetrieveInfo(employeeID);
});
function RetrieveInfo(employeeID) {
 $.ajax({
	  type: "POST",
	  url: "employeepayroll/calculations",
	  data: {
	   "employeeID": employeeID
	  },
	  dataType: 'json',
	  cache: false,
	  success: function(data) {
		   showMessage("Calculation Retrieved Successfully");
		   var ele = $('div.form-group');
		   ele.find('span#employeepayroll-basicsalary').text(data['BasicSalary']);
	  },
	  error: function() {
	   	showError("Server Error.");
	  }
 });
}
$('div#containerPayroll').find('div.add-all button').on('click', function(){
	AssignAllRows();
});

function AssignAllRows(){
	var rowAllowance = $('div.unassigned table#tableAllowanceUnassigned tbody').html();
	$('div.unassigned table#tableAllowanceUnassigned tbody').remove();
	$('div.assigned').find('table#tableAllowanceAssigned tbody').append(rowAllowance);
	var rowDeduction = $('div.unassigned table#tableDeductionUnassigned tbody').html();
	$('div.unassigned table#tableDeductionUnassigned tbody').remove();
	$('div.assigned').find('table#tableDeductionAssigned tbody').append(rowDeduction);
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