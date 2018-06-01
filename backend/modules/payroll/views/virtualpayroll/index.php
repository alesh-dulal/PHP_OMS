<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Virtualemployee;
 ?>

 <?php 
 $this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
  ?>
  <?php 
  $html = "";
  		foreach($model as $mo){
  			$html .= "<option value=".$mo['VirtualEmployeeID'].">".$mo['FullName']."</option>";
  		}
   ?>
 

<div class="container-fluid" id="containerArticlePayroll">
   <div class="row">
      <div class="col-md-12">
         <div class="col-md-4 well">
            <div class="form-group">
               <label>Employee Name</label>
               <select class="employeeID form-control select-single"  name="employeeid" >
                  <option value="0" disabled="true" selected="true">--Select Employee--</option>
                  <?= $html ?>
               </select>
            </div>
            <div class="form-group">
               <label for="monthYear">Month Year</label>
               <?=  monthYearDropdown(); ?>
            </div>
            <div class="form-group">
               <label for="noOfArticles">Total Articles</label>
               <div class="row">
                  <div class="col-md-9">                
                     <input type="text" class="form-control no-of-articles" name="noofarticles" id="noOfArticles">
                  </div>
                  <div class="col-md-3">
                     <button data-id="0" class="btn btn-success calculate" id="calculate" name="calculate" >Calc</button>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <label for="income">Total Income</label>
               <input type="text" class="form-control income" id="income" name="income">
            </div>
            <div class="form-group">
               <label for="bonus">Bonus</label>
               <input type="text" value="0" class="form-control bonus" id="bonus" name="bonus">
            </div>
            <div class="form-group">
               <label for="otherTax">Other Tax</label>
               <input type="text" value="0" class="form-control other-tax" id="otherTax" name="othertax">
            </div>
            <div class="form-group">
               <label for="bonus">Advance</label>
               <input type="text" value="0" class="form-control advance" id="advance" name="advance">
            </div>
            <div class="form-group">
               <label for="remarks">Remarks</label>
               <textarea class="form-control" placeholder="You can leave it blank......" name="remarks" id="remarks" cols="10" rows="2"></textarea>
            </div>
            <div class="form-group form-btn" id="buttons">
               <?= Html::button('Reset', ['class' => 'btn btn-default salary-reset', 'value'=>'reset']) ?>
               <?= Html::button('Save', ['class' => 'btn btn-primary salary-save', 'value'=>'save','data-id'=>'0']) ?>
            </div>
         </div>
         <div class="col-md-8">
            <div id="headContainer" style="position: fixed; left: 50%; top: 50px; padding: 0 15px; background: white;">
               <div class="header">
                  <h2>Article Based Employee's Salary</h2>
                  <h5 >Salary of <span id="monthName"><?= $sentMonth ?></span>-<span id="yearName"><?= $sentYear ?></span></h5>
               </div>
            </div>
            <div class="table-article-payroll"  style=" position: fixed;margin-top: 75px; padding: 0 15px; background: white;">
               <div style="padding-left:calc( 100% - 13em )">
                  <?php echo monthYearFilter();?> 
               </div>
               <table id="articlePayroll" class="table table-bordered article-payroll">
                  <thead>
                     <th>Status</th>
                     <th>Employee Name</th>
                     <th>Total Article</th>
                     <th>Total Income</th>
                     <th>Bonus</th>
                     <th>SST</th>
                     <th>Other TAX</th>
                     <th>Net Income</th>
                     <th>Advance</th>
                     <th>Payable Amount</th>
                  </thead>
                  <tbody>
                     <!-- //salary list will be displayed here -->
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>



 <?php 
$js = <<< JS

$(".select-single").select2();
$(document).ready(function() {
 GetSalary(0, 0);
});
var ele = $('div#containerArticlePayroll');
$(document).on({
 ajaxStart: function() {
  nowLoading();
  $("body").addClass("loading");
 },
 ajaxStop: function() {
  $("body").removeClass("loading");
 }
});

ele.find('select[name="employeeid"]').on('change', function() {
 var employeeid = ele.find('select[name="employeeid"] option:selected').val();
 ele.find('button.salary-save').attr('data-id', employeeid);
});

ele.find('button.calculate').on('click', function() {
 var employeeid = ele.find('select[name="employeeid"] option:selected').val();
 var noofarticles = ele.find('input[name="noofarticles"]').val();
 (employeeid != 0 && noofarticles != " ") ? CalculateIncome(employeeid, noofarticles): showError("Select Employee");
});

ele.find('div.select-filter select[name="monthyearfilter"]').on('change', function() {
 var month = ele.find('div.select-filter select[name="monthyearfilter"] option:selected').attr('att-month');
 var year = ele.find('div.select-filter select[name="monthyearfilter"] option:selected').attr('att-year');
 GetSalary(month, year);
});

function CalculateIncome(id, articles) {
 $.ajax({
  type: "POST",
  url: "virtualpayroll/calculate",
  data: {
   "EmployeeID": id,
   "TotalArticles": articles
  },
  dataType: 'json',
  cache: false,
  success: function(data) {
   if (data.result == true) {
    showMessage(data["message"]);
    ele.find('input[name="income"]').val(data.income);
   } else {
    showError(data["message"]);
   }

  },
  error: function(data) {
   showError(data.message);
  }
 });
}
ele.find('button.salary-reset').on('click', function() {
 resetFields();
});
ele.find('button.salary-save').on('click', function() {
 var id = ele.find('select[name="employeeid"] option:selected').val();
 var month = ele.find('select[name="monthyear"] option:selected').attr('att-month');
 var year = ele.find('select[name="monthyear"] option:selected').attr('att-year');
 var articles = ele.find('input[name="noofarticles"]').val();
 ''
 var income = ele.find('input[name="income"]').val();
 var bonus = ele.find('input[name="bonus"]').val();
 var othertax = ele.find('input[name="othertax"]').val();
 var advance = ele.find('input[name="advance"]').val();
 var remarks = ele.find('textarea[name="remarks"]').val();
 SaveSalary(id, month, year, articles, income, bonus, othertax, advance, remarks);
});

function resetFields() {
 var inputArray = document.querySelectorAll('input');
 inputArray.forEach(function(input) {
  input.value = "";
 });
 $('select[name="monthyear"]').val("0").trigger('change');
 $('select[name="employeeid"]').val("0").trigger('change');
}

function SaveSalary(id, month, year, articles, income, bonus, othertax, advance, remarks) {
 $.ajax({
  type: "POST",
  url: "virtualpayroll/save",
  data: {
   "EmployeeID": id,
   "Month": month,
   "Year": year,
   "TotalArticles": articles,
   "Income": income,
   "Bonus": bonus,
   "OtherTax": othertax,
   "Advance": advance,
   "Remarks": remarks
  },
  dataType: 'json',
  cache: false,
  success: function(data) {
   if (data.result == true) {
    resetFields();
    GetSalary(0, 0);
    showMessage(data["message"]);
   } else {
    showError(data["message"]);
   }
  },
  error: function(data) {
   showError(data.message);
  }
 });
}

function GetSalary(month, year) {
 $.ajax({
  type: "POST",
  url: "virtualpayroll/getemployeepayroll",
  data: {
   "Month": month,
   "Year": year
  },
  dataType: 'json',
  cache: false,
  success: function(data) {
   if (data.result == true) {
    ele.find('div.header span#monthName').text(data.month);
    ele.find('div.header span#yearName').text(data.year);
    ele.find('select[name="monthyearfilter"]').val(data.monthnum);
    ele.find('table.article-payroll tbody').empty("");
    ele.find('table.article-payroll tbody').append(data.tablebody);
   } else {
    showError(data["message"]);
   }
  },
  error: function(data) {
   showError(data.message);
  }
 });
}

ele.find('table#articlePayroll').on('click', 'button.unprocess-salary', function() {
 var EmployeeID = ele.find('table#articlePayroll button.unprocess-salary').closest('tr').attr('attr-empid');
 var EmployeeName = ele.find('table#articlePayroll button.unprocess-salary').closest('tr').attr('attr-name');

 var EmployeeID = ele.find('table#articlePayroll button.unprocess-salary').closest('tr').attr('attr-empid');
 var Month = $(this).attr('attr-month');
 var Year = $(this).attr('attr-year');
 if (confirm("Do You Want To Unprocess Payroll of " + EmployeeName)) {
  Unprocess(EmployeeID, Month, Year);
 } else {
  showMessage("Cancled Salary Unprocess of " + EmployeeName);
 }
});

function Unprocess(ID, Month, Year) {
 $.ajax({
  type: "POST",
  url: "virtualpayroll/unprocess",
  data: {
   "EmployeeID": ID,
   "Month": Month,
   "Year": Year
  },
  dataType: 'json',
  cache: false,
  success: function(data) {
   showMessage(data["message"]);
   GetSalary(0, 0);
  },
  error: function(data) {
   showError(data.message);
  }
 });
}
ele.find('table#articlePayroll').on('click', 'button.pay-salary', function() {
 var EmployeeID = ele.find('table#articlePayroll button.unprocess-salary').closest('tr').attr('attr-empid');
 var EmployeeName = ele.find('table#articlePayroll button.unprocess-salary').closest('tr').attr('attr-name');

 var EmployeeID = ele.find('table#articlePayroll button.unprocess-salary').closest('tr').attr('attr-empid');
 var Month = $(this).attr('attr-month');
 var Year = $(this).attr('attr-year');
 if (confirm("Do You Want To Pay Salary of " + EmployeeName)) {
  PaySalary(EmployeeID, Month, Year);
 } else {
  showMessage("Cancled Salary Payment of " + EmployeeName);
 }
});

function PaySalary(ID, Month, Year) {
 $.ajax({
  type: "POST",
  url: "virtualpayroll/paysalary",
  data: {
   "EmployeeID": ID,
   "Month": Month,
   "Year": Year
  },
  dataType: 'json',
  cache: false,
  success: function(data) {
   showMessage(data["message"]);
   GetSalary(0, 0);
  },
  error: function(data) {
   showError(data.message);
  }
 });
}


JS;

$this->registerJS($js);
?>

<?php  
$this->registerCSS("
.form-group {
     margin-bottom: 2px;
}
 .form-btn{
     float:right;
}
 .header {
     text-align:Center;
}
 span {
     background-color: transparent;
     color: #337ab7;
     text-decoration: none;
}
 span {
     color: #337ab7;
     text-decoration: none;
     background-color: transparent;
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
 .select select.custom-control:hover, .select select:focus {
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
 .select select.custom-control:hover ~ .select_arrow, .select select.custom-control:focus ~ .select_arrow {
     border-top-color: #555;
}
 .select select.custom-control:disabled ~ .select_arrow {
     border-top-color: #555;
}
 #containerArticlePayroll{
     padding: 70px 15px 20px;
}
 #articlePayroll tbody {
     display:block;
     height:300px;
     overflow:auto;
}
 #articlePayroll thead,#articlePayroll tbody tr {
     display:table;
     width:100%;
     table-layout:fixed;
}
 #articlePayroll thead {
     background:#e6e6e6;
     width: calc( 100% - 0em ) 
}

");
 ?>


 <?php 
  function monthYearDropdown()
  {
    $newdate =  strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-6 month");
    $month = date("n", $newdate);
    $monthYear = '<div class="select"><select style="width:100%;" class="custom-control" id="monthyear" name="monthyear" size="1">';
    $monthYear.= '<option value="0" disabled selected="true">--Choose Salary Month & Year--</option>';
    for ($i = 0; $i < 12; $i++)
    {
      $monthtime = mktime(0, 0, 0, $month + $i, 1, 2017);
      $monthnum = date('n', $monthtime);
      $monthYear .= '<option att-year="'.date('Y', $monthtime).'" att-month="'.$monthnum.'"value="'.$monthnum.'">'.date('F Y', $monthtime).'</option>';
    }
    $monthYear.= '</select><div class="select_arrow"></div></div>';
    return $monthYear;
  }

  function monthYearFilter()
  {
    $newdate =  strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-6 month");
    $month = date("n", $newdate);
    $monthYear = "";
    $monthYear .= '<div class="select select-filter"><select style="width:100%" class="custom-control id="monthyearfilter" name="monthyearfilter" size="1">';
    $monthYear.= '<option value="0" selected="true" disabled>--Choose Filter--</option>';
    for ($i = 0; $i < 12; $i++)
    {
      $monthtime = mktime(0, 0, 0, $month + $i, 1, 2017);
      $monthnum = date('n', $monthtime);
      $monthYear .= '<option att-year="'.date('Y', $monthtime).'" att-month="'.$monthnum.'"value="'.$monthnum.'">'.date('F Y', $monthtime).'</option>';
    }
    $monthYear.= '</select><div class="select_arrow"></div></div>';
    return $monthYear;
  }

  ?>

  