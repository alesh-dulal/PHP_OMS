<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\modules\user\models\Employee;
use backend\modules\payroll\models\Payrollattendance;
 ?>
 <h2 align="center">Payroll Attendance</h2>

	<div class="container-fluid" id="containerpayrollSetting">
  <div class="col-lg-12 row">
    <div class="col-lg-4 well create-setting">
      <h4 align="center">Form</h4>
<!-- start of advance form -->
<div class="advance-form">
<?php $form = ActiveForm::begin(); ?>
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
    <div class="form-group">
      <label for="month">Month</label>
      <?php echo months(); ?>
    </div>
    <div class="form-group">
      <label for="year">Year</label>
      <?php echo years(); ?>
    </div>
    <div class="form-group">
      <label for="attendanceDays">Attendance Days</label>
      <input type="text" name="attendancedays" class="form-control attendance-days" id="attendanceDays">
    </div>
    <div class="form-group form-btn" id="buttons">
        <?= Html::button('Reset', ['class' => 'btn btn-default setting-reset', 'value'=>'reset']) ?>
        <?= Html::button('Save', ['class' => 'btn btn-primary setting-save', 'value'=>'save','data-id'=>'0']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
<!-- end of form -->

    </div>
    <div class="col-lg-8 show-amendment">
      <div class="row"><div class="col-sm-6 text-right"><h4>Attendances</h4></div><div class="col-sm-6"><div class="pull-right">
      <?php echo monthYearDropdown();?></div></div></div>
       <table class="table table-bordered table-responsive" id="amendmentTable">
          <thead>
            <tr>
              <th>Employee Name</th>
              <th>Month</th>
              <th>Year</th>
              <th>Attendance Days</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
                <!-- data will be populated here -->
          </tbody>
        </table>
    </div>
  </div>
</div>

<?php 
$js = <<< JS

$(document).on({
    ajaxStart: function() { nowLoading(); $("body").addClass("loading");    },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});


    var ele = $('div#containerpayrollSetting');
$(document).ready(function(){var yearValue = "";var monthValue = "";GetData(yearValue , monthValue);});
$('div#buttons').find('button.setting-reset').on('click', function(){
  ele.find('button.setting-save').attr('data-id',0);
    resetFields();
});
function resetFields(){

    var inputArray = document.querySelectorAll('input[type="text"]');
    inputArray.forEach(function(input){
        input.value = "";
    });
    $('select.form-select').val("0").trigger('change');
    $('select[name="Payrollattendance[EmployeeID]"]').val("0").trigger('change');
}


ele.find('table').on('click','span.edit', function(){
    GetSingleRecord($(this));
});
function GetSingleRecord(edit){
    ele.find('button.setting-save').attr('data-id',edit.attr('data-id'));
    ele.find('select[name="Payrollattendance[EmployeeID]"]').val(edit.parents('tr').find('td:eq(0)').attr('emp-id')).trigger('change');
    ele.find('select[name="month"]').val(edit.parents('tr').find('td:eq(1)').attr('attr-month'));
    ele.find('select[name="year"]').val(edit.parents('tr').find('td:eq(2)').text());
    ele.find('input[name="attendancedays"]').val(edit.parents('tr').find('td:eq(3)').text());
  }

ele.find('table').on('click','span.deactivate', function(){

  var id = $(this).attr('data-id');
    if(confirm("Do You Want To Deactivate")){
      Deactivate(id);
    }else{
      showMessage("Cancled Amendment Deactivation");
    }
});

  function Deactivate(ID) {
  var monthValue = ele.find('select[name="monthyear"] option:selected').attr('att-month');
  var yearValue = ele.find('select[name="monthyear"] option:selected').attr('att-year');
        $.ajax({
            type: "POST",
            url: "payrollattendance/deactivate",
            data: {
              "ID":ID 
            },
            dataType:'json',
            cache: false,
            success: function(data) {
              if(data.result == true){
                showMessage(data.message);
                GetData(yearValue, monthValue); 
              }else{
                showError(data.message);
              }
            },

            error:function(data){
                showError(data.message);
            }
        });
    }
ele.find('select[name="monthyear"]').on('change', function(){
  var monthValue = ele.find('select[name="monthyear"] option:selected').attr('att-month');
  var yearValue = ele.find('select[name="monthyear"] option:selected').attr('att-year');
    GetData(yearValue, monthValue);
  });


function GetData(yearValue, monthValue) {
        $.ajax({
            type: "POST",
            url: "payrollattendance/getsetting",
            data: {
              "year":yearValue,
              "month":monthValue
            },
            dataType:'json',
            cache: false,
            success: function(data) {
              ele.find('select[name="monthyear"]').val(data.month);
              $('div#containerpayrollSetting').find('table tbody').empty();  
              $('div#containerpayrollSetting').find('table tbody').append(data.html);  
            },

            error:function(data){
                showError(data.message);
            }
        });
    }



$('div#buttons').find('button.setting-save').on('click', function(){
  
  var isnewrecord = ele.find('button.setting-save').attr('data-id');
  var name = ele.find('select[name="Payrollattendance[EmployeeID]"]').val();
  var month = ele.find("select[name='month'] option:selected").val();
  var year = ele.find("select[name='year'] option:selected").val();
  var attendancedays = ele.find("input[name='attendancedays']").val();
  
  SaveData(isnewrecord, name, month, year, attendancedays);
});
function SaveData( isnewrecord, name, month, year, attendancedays) {
        $.ajax({
            type: "POST",
            url: "payrollattendance/savesettings",
            data: {
                "IsNewRecord": isnewrecord,
                "Name": name,
                "Month": month,
                "Year": year,
                "AttendanceDays": attendancedays
            },
            dataType:'json',
            cache: false,
            success: function(data) {
              console.log("Hello");
                showMessage(data.message);
                GetData(data.year, data.month);
                resetFields();
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
$this->registerCSS("
.form-btn{
    float:right;
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
}    .select select.custom-control {
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
.select select.custom-control:hover ~ .select_arrow,
.select select.custom-control:focus ~ .select_arrow {
    border-top-color: #555;
}
.select select.custom-control:disabled ~ .select_arrow {
    border-top-color: #555;
}
    ");
 ?>

 <?php 
  function months($selctedMonth ='january'){

      $months='<div class="select"><select name="month" class="form-select custom-control month" id="month" size="1">';
       $months .= '<option value="0" disabled>--Select Month--</option>';
      for ($i = 12; $i > 0; $i--) {
          $time = strtotime(sprintf('-%d months', $i));   
          $label = date('F', $time); 
          $selctedM = strtolower($selctedMonth) == strtolower($i) ? 'selected' : '';
          $months.="<option value='".date("n", strtotime($label))."'  $selctedM >$label</option>";
      }  
      $months.='</select><div class="select_arrow"></div></div>';
      return $months;
  }

  function years(){
    $starting_year = date('Y', strtotime('-3 year'));
    $ending_year = date('Y', strtotime('+5 year'));
    $years = '<div class="select"><select class="form-select custom-control id="year" name="year" size="1">';
    $years .= '<option value="0" disabled>--Select Year--</option>';
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


  function monthYearDropdown()
  {
    $newdate =  strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-6 month");
    $month = date("n", $newdate);
    $monthYear = '<div class="select"><select style="width:100%" class="custom-control id="monthyear" name="monthyear" size="1">';
    $monthYear.= '<option value="0" disabled selected="true">--Choose Filter--</option>';
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

