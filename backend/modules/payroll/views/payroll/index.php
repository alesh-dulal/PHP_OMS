<?php 
$this->title="Payroll";
$this->registerJsFile("@web/backend/web/js/table2excel.js",['depends' => [\yii\web\JqueryAsset::className()]]);

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
?>

<div class="container-fluid" id="containerPayroll">
	<div id="headContainer" style="position: fixed; width: 100%; left: 0px; top: 50px; padding: 0 15px; background: white;">
	<div class="header">
		<h2>Payroll</h2>
		<h5 >Salary is processed till <span id="monthName">ThisMonth </span>-<span id="yearName">ThisYear</span></h5>
	</div>

	<div class="row processing">
		<div class="pull-left" id="pickYearMonth">
			<select name="yearpicker" id="yearPicker"></select>
			<select name="monthpicker" id="monthPicker"></select>
			<button id="go">GO</button>
			<button id="clear">Clear</button>
		</div>
		<div class="pull-right" id="actions">
			<input type="text" id="searchInput" placeholder="Search">
			<button id="bankStatement">Bank Statement</button>
			<button id="isPaidNow">Is Paid Now</button>
			<button name="create_excel" id="create_excel" class="">Export TO Excel</button> 
		</div>
	</div>
			<hr/>
		</div>
	<div class="row row-payroll-table"  style="padding-top: 130px;">
		<table class="table table-bordered" id="payrollTable">
			<thead>
				<th><input name="headercheck" id="checkAll" type="checkbox"></th>
				<th>Employee Name</th>
				<th style="display: none;">Bank Account Number</th>
				<th style="display: none;">EmployeeID</th>
				<th style="display: none;">Email</th>
				<th>Basic Salary</th>
				<?php 
					foreach ($Rows as $key => $Row)
					{ 
						if ($Row['IsAllowance'] == 0)
						{
							echo "<th>".$Row['Title']."</th>";
						}
					}
				?>

				<th>Total Allowance</th>
				<?php 
					foreach ($Rows as $key => $Row)
					{ 
						if ($Row['IsAllowance'] == 1)
						{
							echo "<th>".$Row['Title']."</th>";
						}
					}
				?>
				<th>Total Deduction</th>
				<th>Income</th>
				<th>Absent Days</th>
				<th>Absent Deduction</th>
				<th>Gross Income</th>
				<th>SST</th>
				<th>Other TAX</th>
				<th>Net Income</th>
				<th>Advance Deduction</th>
				<th>Payable Amount</th>
				<th>Remarks</th>
			</thead>
			<tbody>				
			</tbody>
		</table>
	</div>
	<div class="emailPortion col-lg-6">
		<div class="row form-group">
			<label for="emailNote"> Email Note </label>
			<textarea id="emailNote" name="note" cols="67"></textarea>
		</div>
		<button id="processNow">Process Now</button>
	</div>
</div>

<!-- start of modal -->
<div id="payrollProcessModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pay Salary</h4>
      </div>
      <div class="modal-body">
      	<h4 align="center">List of Processed Payroll</h4>
        	<table id="payTable">
        		<thead>
        			<th><input type="checkbox" name="headerpay" id="checkAllPay"></th>
        			<th>Employee Name</th>
        			<th>Payable Amount</th>
        		</thead>
        		<tbody>
        			
        		</tbody>
        	</table>
        <label for=""></label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="payPayroll">Pay</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end of modal -->


<?php 
$js = <<< JS
$(document).ready(function(){
	var table = $('div#containerPayroll').find('table#payrollTable');
	table.find('input[name="headercheck"]').on('change', function(){
		var boxes = table.find('input.payroll-check');
		boxes.each(function(){
			if (table.find('input[name="headercheck"]').is(':checked')) {
				boxes.prop('checked',true);
			}else{
				boxes.prop('checked',false);
			}
		});
	});
$(document).on({
    ajaxStart: function() { nowLoading(); $("body").addClass("loading");    },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});

	var tablePay = $('div#payrollProcessModal').find('div.modal-body table#payTable');
	tablePay.find('input[name="headerpay"]').on('change', function(){
		var box = tablePay.find('input.payroll-pay');
		box.each(function(){
			if (tablePay.find('input[name="headerpay"]').is(':checked')) {
				box.prop('checked',true);
			}else{
				box.prop('checked',false);
			}
		});
	});

	var year = new Date().getFullYear();
	var endRange = new Date().getFullYear()+5;
	var startRange = new Date().getFullYear()-5;
	$('#yearPicker').append('<option value="0" selected disabled>Select Year</option>');
	for (year = startRange; year <= endRange; year++)
	{
		$('#yearPicker').append($('<option />').val(year).html(year));
	}

	var monthNames = ["Select Month","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	var month = (new Date()).getMonth();
	//$('#monthPicker').append('<option selected disabled>Select Month</option>');
	for (month=0; month < monthNames.length; month++) {
		if(month == 0){
	  		$('#monthPicker').append($('<option selected disabled />').val(month).html(monthNames[month]));
		}else{
	  		$('#monthPicker').append($('<option />').val(month).html(monthNames[month]));
		}
	}
	CalculatePayroll();

});

var head = $('div#pickYearMonth');
head.find('button#go').on('click', function(){
	var pickedYear = head.find('select[name="yearpicker"]').val();
	var pickedMonth = head.find('select[name="monthpicker"]').val();
	if(pickedYear == null || pickedMonth == null){
		showError("Relevant Message.");
	}else{
		GetPayroll(pickedYear, pickedMonth);
	}
});
function CalculatePayroll(){
	$.ajax({
        type: "POST",
        url: "payroll/calcpayroll",
        data: {
        },
        dataType:'json',
        cache: false,
        success: function(data) {
	    	if(data['oldhtml'] != undefined)
	    	{
				var yearValue = parseInt(data.oldhtml["year"]);
				var monthValue = parseInt(data.oldhtml["month"]);
				PayrollMonthYear(yearValue, monthValue);

				$('div#containerPayroll').find('div.row-payroll-table table tbody').append(data.oldhtml["tableBody"]);
				showMessage(data["message"]);
				
	    	}
	    	else
	    	{
	    		console.log("Hello with New data.");
				var yearValue = parseInt(data["year"]);
				var monthValue = parseInt(data["month"]);
				PayrollMonthYear(yearValue, monthValue);
				$('div#containerPayroll').find('div.row-payroll-table table tbody').append(data["html"]);
	        	showMessage(data["message"]);
	    	}
        },
        error:function(data){
            showError("Relevant Message error.");
        }
    });
}

function GetPayroll(year, month){
	$.ajax({
        type: "POST",
        url: "payroll/getpayroll",
        data: {
            "year": year,
            "month": month
        },
        dataType:'json',
        cache: false,
        success: function(data) {
			var yearValue = parseInt(data['year']);
			var monthValue = parseInt(data['month']);
			PayrollMonthYear(yearValue, monthValue);
			var tableBody = $('div#containerPayroll').find('div.row-payroll-table table tbody');
			if(data['tableBody'] == ""){
				tableBody.html("");
				showError("No Data Available.");
			}else{
				tableBody.html("");
			tableBody.append(data['tableBody']);
			}
            showMessage("Relevant Message success.");
        },
        error:function(data){
            showError("Relevant Message error.");
        }
    });
}

function PayrollMonthYear(yearValue, monthValue){
	var monthName = ["","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	$('div#containerPayroll').find('span#monthName').text(monthName[monthValue]);
	$('div#containerPayroll').find('span#yearName').text(yearValue);
	$('div#pickYearMonth').find('select#monthPicker').val(monthValue);
	$('div#pickYearMonth').find('select#yearPicker').val(yearValue);
}

$('div#pickYearMonth').find('button#clear').on('click', function(){
	$('select').val("0").trigger('change');
});



$('div#containerPayroll').find('div.emailPortion  button#processNow').click(function(){

	var month = $('div#pickYearMonth').find('select#monthPicker').val();
	var year = $('div#pickYearMonth').find('select#yearPicker').val();

	var message = $('div#containerPayroll').find('div.emailPortion textarea#emailNote').val();
	message = message || " ";

	var headers = $("#payrollTable thead th").filter(function() {return !$(this).find('#checkAll').length;
	}).map(function() {return $(this).text().trim();}).get();

	var arr = $('#payrollTable .payroll-check:checked').map(function() {
	var obj = {};
	$(this).parent().siblings().each(function(i) {
		obj[headers[i]] = $(this).text().trim();
	})
		return obj;
	}).get();


	SavePayroll(arr, month, year, message);

});

function SavePayroll(array, month , year, message)
{
	$.ajax({
        type: "POST",
        url: "payroll/savepayroll",
        data: {
        	"array":array,
        	"month":month,
        	"year":year,
        	"message":message
        },
        dataType:'json',
        dataType:'json',
        cache: false,
        success: function(data) {
	    	showMessage(data["message"]);
	    	location.reload();
        },
        error:function(data){
            showError(data.message);
        }
    });
}

$('div#actions').find('button#create_excel').on('click', function(){
	var month = $('span#monthName').text();
	$("#payrollTable").table2excel({
            filename: "Payroll-"+month+".xls"
        });
});

$('div#actions').find('input#searchInput').keyup(function(){
	var searchFor = $('div#actions').find('input#searchInput').val();
	search(searchFor);
});

function search(input) {
  var filter = input.toUpperCase();
  var table = $('div#containerPayroll').find('div.row-payroll-table table');
  var tr = table.find('tr');
  for (var i = 0; i < tr.length; i++)
  {
    var td = tr[i].getElementsByTagName('td')[1];
    if (td) 
    {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1)
      {
        tr[i].style.display = "";
      }
      else
      {
        tr[i].style.display = "none";
      }
    }       
  }
}

// for modal of salary pay which are processed previously
$('div#actions').find('button#isPaidNow').on('click', function(){
	$('div#payrollProcessModal').find('div.modal-body table#payTable tbody tr').remove();
	$('div#payrollProcessModal').modal();
	var obj = [];
	obj = EmployeeProcessedPayroll();
	console.log(obj);

	if(obj.length == 0){
		$('div#payrollProcessModal').find('div.modal-body table#payTable tbody').append('<tr><td colspan="3" align="center">No Salary Processed</td></tr>');
	}else{
		$.each(obj, function (index, value) {
		var val = [];
		val = value;
	  	$('div#payrollProcessModal').find('div.modal-body table#payTable tbody').append('<tr><td><input attr-empid ='+value['EmployeeID']+' class="payroll-pay" type ="checkbox"></td><td>'+value['Employee Name']+'</td><td>'+value['Payable Amount']+'</td></tr>');
	});
	}
 });
function EmployeeProcessedPayroll(){
	var array = [];
    var headers = [];
    $('#payrollTable th').each(function(index, item) {if(index == 0){return true;}else{headers[index] = $(item).html();}});

    $('#payrollTable tr.processed').each(function() {
        var arrayItem = {};
        $('td', $(this)).each(function(index, item) {if(index == 0){return true;}else{arrayItem[headers[index]] = $(item).html();}});
        array.push(arrayItem);
    });
    return array;
}


$('div#payrollProcessModal').find('div.modal-footer button#payPayroll').on('click', function(){

			var arr = $('div#payrollProcessModal').find('div.modal-body table#payTable .payroll-pay:checked').map(function() {
			var obj = {};
			$(this).each(function(i) {
				obj["EmployeeID"] = $(this).attr('attr-empid');
			})
				return obj;
			}).get();

	PayPayroll(arr);

});


function PayPayroll(array){
	$.ajax({
        type: "POST",
        url: "payroll/paypayroll",
        data: {
        	"array":array,
        },
        dataType:'json',
        cache: false,
        success: function(data) {
	    	showMessage(data["message"]);
	    	location.reload()
        },
        error:function(data){
            showError(data.message);
        }
    });
}

$('div#containerPayroll').find('table#payrollTable').on( 'click','button.unprocess-salary', function(){
	var EmployeeID = $('div#containerPayroll').find('table#payrollTable button.unprocess-salary').closest('tr').attr('attr-empid');
	var Month = $(this).attr('attr-month');
	var Year = $(this).attr('attr-year');
	Unprocess(EmployeeID, Month, Year);
});

function Unprocess(ID, Month, Year){
	$.ajax({
        type: "POST",
        url: "payroll/unprocess",
        data: {
        	"EmployeeID":ID,
        	"Month":Month,
        	"Year":Year
        },
        dataType:'json',
        cache: false,
        success: function(data) {
	    	showMessage(data["message"]);
	    	location.reload()
        },
        error:function(data){
            showError(data.message);
        }
    });
}

JS;
$this->registerJS($js);
?>
<?php
	$this->registerCss("
		body{
			padding-left:10px;
		}
	.header{
		text-align:Center;
	}
	.row{
		margin-top:10px;
	}
	button#processNow{
		float:right;
	}
	textarea#emailNote{
	  vertical-align: top;
	  resize: none;
	}

.focus {
  background-color: #ff00ff;
  color: #fff;
  cursor: pointer;
  font-weight: bold;
}

.pageNumber {
  padding: 2px;
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td,
th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

#payrollTable{
	font-size: 10px;
}


#containerPayroll{
	padding: 70px 15px 20px;
}


#payrollTable tbody {
		display:block;
		height:500px;
		overflow:auto;
	}

	#payrollTable thead,#payrollTable tbody tr {
		display:table;
		width:100%;
		table-layout:fixed;
	}

	#payrollTable thead {
		background:#e6e6e6;
		width: calc( 100% - 1em )
	}


	

");
?>