<?php 
use yii\helpers\Html;
$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title="Leave Accrue";
$html = "";
foreach($LeaveType as $LT){
    $html .= "<option value=".$LT['ListItemID'].">".$LT['Title']."</option>";
}
?>

<h3 align="center">Leave Accrue</h3>
<div class="pull-right" id="actions">
  <input type="text" id="searchInput" placeholder="Search">
</div>
<div class="container" id="containerLeaveAccrue">
   <div class="col-md-12">
      <div class="well col-md-4">
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
               <input type="text" id="accrueDays" class="form-control accrue-days" name="accruedays" placeholder="Insert Accrue Days.." aria-describedby="basic-addon2">
               <span class="input-group-addon" id="basic-addon2">Days</span>
            </div>
         </div>
         <button type="submit" id="saveAccrue" name="saveaccrue" class="save-accrue btn btn-danger">Accrue</button>
      </div>
      <div class="col-md-8">
        <div class="table-employee-leave" id="tableEmployeeLeave">
          <table class="table table-bordered leave-table" id="leaveTable">
            <thead>
              <th>Name</th>
              <th>Leave Type</th>
              <th>Earned</th>
              <th>Balance</th>
            </thead>
            <tbody>
              <?php 
              $html = "";
              foreach ($EmployeeLeave as $key => $EL) {

                $html .="<tr>";
                $html .="<td>".$EL['FullName']."</td>";
                $html .="<td>".$EL['Title']."</td>";
                $html .="<td>".$EL['Earned']."</td>";
                $html .="<td>".$EL['Balance']."</td>";
                $html .="</tr>";

              }
              echo $html;
               ?>
            </tbody>
          </table>
        </div>
      </div>
   </div>
</div>



 <?php 
$js = <<< JS
$(".select-leave-type").select2();

var ele = $('div#containerLeaveAccrue');
ele.find('div.well').on('click', 'button.save-accrue', function() {
var LeaveTypeID = ele.find('select[name="leavetype"] option:selected').val();
var AccrueDays = ele.find('input[name="accruedays"]').val();
var AccrueMonth = ele.find('select[name="Accrue[Month]"] option:selected').val();
var AccrueYear = ele.find('select[name="accrue[Year]"] option:selected').val();
if(LeaveTypeID && AccrueDays && AccrueMonth && AccrueYear != " "){	
 if (confirm("Do You Want To Proceed The Accrual.")) {
  Accrue(LeaveTypeID, AccrueDays, AccrueMonth, AccrueYear);
 } else {
  showMessage("Cancled Accrue Process");
 }
}else{
	showError("Select Input Properly.")
}
});

function Accrue(ID, Days, Month, Year) {
 $.ajax({
  type: "POST",
  url: "saveleaveaccrue",
  data: {
   "LeaveTypeID": ID,
   "Days": Days,
   "Month": Month,
   "Year": Year
  },
  dataType: 'json',
  cache: false,
  success: function(data) {
   showMessage(data["message"]);
   setTimeout(function() {
    location.reload();
}, 3000);
  },
  error: function(data) {
   showError(data.message);
  }
 });
}

$('div#actions').find('input#searchInput').keyup(function(){
  var searchFor = $('div#actions').find('input#searchInput').val();
  search(searchFor);
});

function search(input) {
  var filter = input.toUpperCase();
  var table = ele.find('table#leaveTable');
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

#leaveTable tbody {
    display:block;
    height:300px;
    overflow:auto;
  }

  #leaveTable thead,#leaveTable tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
  }

  #leaveTable thead {
    background:#e6e6e6;
    width: calc(100%)
  }
  
	");
 ?>

 <?php

function months($selctedMonth = 'january')
{
  $months = '<div class="select"><select name="Accrue[Month]" class="form-select custom-control month" id="accrue-month" size="1">';
  $months.= '<option value="0" disabled>--Select Accrue Month--</option>';
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
  $years = '<div class="select"><select class="form-select custom-control id="accrue-year" name="accrue[Year]" size="1">';
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

  $years.= '</select><div class="select_arrow"></div></div>';
  return $years;
}


?>
