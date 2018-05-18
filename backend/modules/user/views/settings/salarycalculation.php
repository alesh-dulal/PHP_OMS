<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
?>

<h2 align="center">Salary Calculation Amendment</h2>

<div class="container-fluid" id="containerAmendment">
  <div class="col-lg-12 row">
    <div class="col-lg-4 well create-amendment">
      <h4 align="center">Form</h4>
<!-- start of advance form -->
<div class="advance-form">

    <div class="form-group">
      <label for="amendmentName">Name</label>
      <input type="text" name="amendmentname" class="form-control amendment-name" id="amendmentName">
    </div>
    <div class="form-group">
      <label for="workHourPerDay">Work Hour Per Day</label>
      <input type="text" name="workhourperday" class="form-control work-hour-per-day" id="workHourPerDay">
    </div>
    <div class="form-group">
      <label for="paidLeavePerMonth">Paid Leave Per Month</label>
      <input type="text" name="paidleavepermonth" class="form-control paid-leave-per-month" id="paidLeavePerMonth">
    </div>
    <div class="form-group">
      <label for="allowedOTHours">Maximum Allowed OT Hours</label>
      <input type="text" name="allowedothours" class="form-control allowed-ot-hours" id="allowedOTHours">
    </div>
    <div class="form-group">
      <label for="paidLeavePerMonth">OT Hours Salary Calculation</label>
      <input type="text" name="othourssalarycalculation" class="form-control ot-hours-salary-calculation" id="OTHoursSalaryCalculation">
    </div>
    <div class="form-group">
      <label for="paidLeavePerMonth">Less Work Hour Salary Calculation</label>
      <input type="text" name="lessworkhoursalarycalculation" class="form-control less-work-hours-salary-calculation" id="lessWorkHoursSalaryCalculation">
    </div>
    <div class="form-group form-btn" id="buttons">
        <?= Html::button('Reset', ['class' => 'btn btn-default amendment-reset', 'value'=>'reset']) ?>
        <?= Html::button('Save', ['class' => 'btn btn-primary amendment-save', 'value'=>'save','data-id'=>'0']) ?>
    </div>

</div>
<!-- end of form -->

    </div>
    <div class="col-lg-8 show-amendment">
      <h4 align="center">Amendments</h4>
       <table class="table table-bordered table-responsive" id="amendmentTable">
          <thead>
            <tr>
              <th>Amendment Name</th>
              <th>Work Hour /Day</th>
              <th>Paid Leave /Month</th>
              <th>Allowed OT Hours /Day</th>
              <th>OT Hours Salary Calculation</th>
              <th>Less Working Hour Salary Deduction</th>
              <th>Actions</th>
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
$(document).ready(function(){GetData();});
$('div#buttons').find('button.amendment-reset').on('click', function(){
    resetFields();
});

function resetFields(){
    var inputArray = document.querySelectorAll('input[type="text"]');
    inputArray.forEach(function(input){
        input.value = "";
    });
}

$('div#buttons').find('button.amendment-save').on('click', function(){
  var ele = $('div#containerAmendment');
  var name = ele.find("input[name='amendmentname']").val();
  var workhourperday = ele.find("input[name='workhourperday']").val();
  var paidleavepermonth = ele.find("input[name='paidleavepermonth']").val();
  var allowedothours = ele.find("input[name='allowedothours']").val();
  var othourssalarycalculation = ele.find("input[name='othourssalarycalculation']").val();
  var lessworkhoursalarycalculation = ele.find("input[name='lessworkhoursalarycalculation']").val();
  SaveData(name, workhourperday, paidleavepermonth, allowedothours, othourssalarycalculation, lessworkhoursalarycalculation);
});
function SaveData(name, workhourperday, paidleavepermonth, allowedothours, othourssalarycalculation, lessworkhoursalarycalculation) {
        $.ajax({
            type: "POST",
            url: "saveamendmentdata",
            data: {
                "Name": name,
                "WorkHourPerDay": workhourperday,
                "PaidLeavePerMonth": paidleavepermonth,
                "AllowedOTHours": allowedothours,
                "OthoursSalaryCalculation": othourssalarycalculation,
                "LessWorkHourSalaryCalculation": lessworkhoursalarycalculation
            },
            dataType:'json',
            cache: false,
            success: function(data) {
                showMessage(data.message);
                GetData();
                resetFields();
            },

            error:function(data){
                showError(data.message);
            }
        });
    }
function GetData() {
        $.ajax({
            type: "POST",
            url: "getamendments",
            data: {  
            },
            dataType:'json',
            cache: false,
            success: function(data) {
              $('div#containerAmendment').find('table tbody').empty();  
              $('div#containerAmendment').find('table tbody').append(data.html);  
            },

            error:function(data){
                showError(data.message);
            }
        });
    }

$('div#containerAmendment').find('table.table tbody').on('click',' span.edit', function(){
  var id = $(this).attr('data-id');
    if(confirm("Do You Want To Deactivate")){
      deactivateAmendment(id);
    }else{
      showMessage("Cancled Amendment Deactivation");
    }
});
function deactivateAmendment(id) {
        $.ajax({
            type: "POST",
            url: "deactivateamendment",
            data: { 
              "ID":id
            },
            dataType:'json',
            cache: false,
            success: function(data) {
              showMessage(data.message);
              GetData();
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

    ");
 ?>