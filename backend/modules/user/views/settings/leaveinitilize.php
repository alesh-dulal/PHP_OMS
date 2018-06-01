<?php 
use yii\helpers\Html;
$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title="Leave Initialize";
$html = "";
$htmlemp = "";

foreach($LeaveType as $LT){
    $html .= "<option value=".$LT['ListItemID'].">".$LT['Title']."</option>";
}
?>
<?php 
foreach ($resultEmp as $key => $emp) {
    $htmlemp .= "<option value=".$emp['EmployeeID'].">".$emp['FullName']."</option>";
}
 ?>
<h3 align="center">Leave Initialization</h3>
<div class="container" id="containerLeaveInit">
   <div class="col-md-12">
      <div class="well col-md-4">
         <div class="form-group">
            <label for="leaveType">Employee Name</label>
            <select class="employeeID form-control select-employee-name"  name="employeename" id="employeeName" >
               <option value="0" disabled="true" selected="true">--Select Employee Name--</option>
               <?= $htmlemp ?>
            </select>
         </div>         
         <div class="form-group">
            <label for="leaveType">Leave Type</label>
            <select class="employeeID form-control select-leave-type"  name="leavetype" id="leaveType" >
               <option value="0" disabled="true" selected="true">--Select Leave Type--</option>
               <?= $html ?>
            </select>
         </div>
         <div class="form-group">
        <label for="month">Month
        </label>
        <?php echo months(); ?>
    </div>
    <div class="form-group">
        <label for="year">Year
        </label>
        <?php echo years(); ?>
    </div> 
         <div class="form-group">
            <label for="accruDays">Accrue Days</label>
            <div class="input-group">
               <input type="text" id="initDays" class="form-control init-days" name="initdays" placeholder="Insert Initializing Leave Days.." aria-describedby="basic-addon2">
               <span class="input-group-addon" id="basic-addon2">Days</span>
            </div>
         </div>
         <button type="submit" id="saveInit" name="saveinit" class="save-init btn btn-danger">Initialize</button>
      </div>
   </div>
</div>
<?php 
$js = <<< JS
$(document).on({
    ajaxStart: function() { nowLoading(); $("body").addClass("loading");    },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});
$(".select-leave-type").select2();
$(".select-employee-name").select2();
$(".month").select2();
$(".year").select2();

var ele = $('div#containerLeaveInit');

ele.find('button.save-init').on('click', function(){
	var EmployeeID = ele.find('select[name="employeename"] option:selected').val();
	var LeaveTypeID = ele.find('select[name="leavetype"] option:selected').val();
	var Month = ele.find('select[name="Init[Month]"] option:selected').val();
	var Year = ele.find('select[name="Init[Year]"] option:selected').val();
	var Days = ele.find('input[name="initdays"]').val();
	SaveInit(EmployeeID, LeaveTypeID, Month, Year, Days);
});

function SaveInit(EmployeeID, LeaveTypeID, Month, Year, Days){
	$.ajax({
              type: "POST",
              url: "saveleaveinit",
              data: {
                    "EmployeeID": EmployeeID,
                    "LeaveTypeID": LeaveTypeID,
                    "Month": Month,
                    "Year": Year,
                    "Days": Days,
              },
              dataType: 'json',
              cache: false,
              success: function(data) {
               if(data.result == true){
					showMessage(data.message);
					resetFields();
               	}else{
               		showError(data.message);
               	}
              },
              error: function(data) {
               
              }
             });
}

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
	button.save-accrue{
		float:right;
	}
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
  $months = '<div class="select"><select name="Init[Month]" class="form-select custom-control month" id="accrue-month" size="1">';
  $months.= '<option value="0" disabled>--Select Accrue Month--</option>';
  for ($i = 12; $i > 0; $i--)
  {
    $time = strtotime(sprintf('-%d months', $i));
    $label = date('F', $time);
    $selctedM = strtolower($selctedMonth) == strtolower($i) ? 'selected' : '';
    $months.= "<option value='" . date("n", strtotime($label)) . "'  $selctedM >$label</option>";
  }

  $months.= '</select><div class=""></div></div>';
  return $months;
}

function years()
{
  $starting_year = date('Y', strtotime('-3 year'));
  $ending_year = date('Y', strtotime('+5 year'));
  $years = '<div class="select"><select class="form-select custom-control year" id="accrue-year" name="Init[Year]" size="1">';
  $years.= '<option value="0" disabled>--Select Accrue Year--</option>';
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

  $years.= '</select><div class=""></div></div>';
  return $years;
}


?>
