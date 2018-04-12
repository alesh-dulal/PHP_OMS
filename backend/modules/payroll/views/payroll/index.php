<?php 
$this->title="Payroll";
$this->registerJsFile("@web/backend/web/js/table2excel.js",['depends' => [\yii\web\JqueryAsset::className()]]);

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
?>

<div class="container" id="containerPayroll">
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

	<div class="row row-payroll-table">
		<table class="table table-bordered" id="payrollTable">
			<thead>
				<th>Employee Name</th>
				<th style="display: none;">Bank Account Number</th>
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
<?php 
$js = <<< JS
$(document).ready(function(){
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
	    		console.log("Hello with existing data.");
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



$('div#containerPayroll').find('div.pull-right button#isPaidNow').click(function(){
	var month = $('div#pickYearMonth').find('select#monthPicker').val();
	var year = $('div#pickYearMonth').find('select#yearPicker').val();

	var array = [];
    var headers = [];
    $('#payrollTable th').each(function(index, item) {
        headers[index] = $(item).html();
    });
    $('#payrollTable tr').has('td').each(function() {
        var arrayItem = {};
        $('td', $(this)).each(function(index, item) {
            arrayItem[headers[index]] = $(item).html();
        });
        array.push(arrayItem);
    });
	SavePayroll(array, month, year);

});

function SavePayroll(array, month , year)
{
	$.ajax({
        type: "POST",
        url: "payroll/savepayroll",
        data: {
        	"array":array,
        	"month":month,
        	"year":year
        },
        dataType:'json',
        cache: false,
        success: function(data) {
	    	showMessage(data["message"]);
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
    var td = tr[i].getElementsByTagName('td')[0];
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

JS;
$this->registerJS($js);
?>
<?php
	$this->registerCss("
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

");
?>