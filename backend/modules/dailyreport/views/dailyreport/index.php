<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;
use backend\modules\user\models\Role;
use backend\modules\user\models\Employee;
use backend\modules\dailyreport\models\Dailyreport;

 $this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
 

$this->title = "Daily Report";

/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\Holiday */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (Yii::$app->session->hasFlash('AttendanceAddedFlash')): ?>
  <div class="alert alert-info alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('AttendanceAddedFlash') ?>
  </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('AttendanceAlreadyExist')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('AttendanceAlreadyExist') ?>
  </div>
<?php endif; ?>

<div class="container" id="dailyReportContainer">
  <h4 align="center">Daily Report</h4>
  <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#dailyReport">Daily Report</a>
        </li>
        <?php
        $SubmittedReport = 0;
        $htmlSubmitted = "";
        $htmlVerified = "";
        $TargetMessage = "<span class='btn btn-danger btn-xs'>Target Not Met</span>";
        $LateMessage = "<span class='btn btn-danger btn-xs'>Spent Less Time</span>";
        // echo "<pre>"; print_r($data); die();
        foreach ($data as $key => $dat) {
          $msg = ($dat['TotalTask']< $dat['Target'])?$TargetMessage:" ";
          $PunchIn = timeToSec($dat['Punchin']);
          $PunchOut = timeToSec($dat['Punchout']);
          $msgPunchIn = ($dat['LoginTime'] > $PunchIn)?"danger-text":"";
          $msgPunchOut = ($dat['ExitTime'] < $PunchOut)?"danger-text":" ";
          $msgLess = ($dat['LoginTime'] > $PunchIn)?$LateMessage:" ";

          if($dat['IsSubmitted'] == 1 && $dat['IsPending'] == 1 && $dat['IsAccepted'] == 0){
            $htmlSubmitted .= "<tr>"; 
            $htmlSubmitted .= "<td>".$dat['ReportBy']."</td>";
            $htmlSubmitted .= "<td>"
              ."<strong>Day</strong>: ".$dat['Day']."<br/>".date("l",strtotime($dat['Day']))."<br/>"
              ."<span class=".$msgPunchIn."><strong>Login Time: </strong>".date("h:i A", strtotime(getTime($dat['LoginTime'])))."</span><br/>"
              ."<span class=".$msgPunchOut."><strong>Exit Time: </strong>".date("h:i A", strtotime(getTime($dat['ExitTime'])))."</span><br/>"
              ."<strong>Stay Time: </strong>".gethms($dat['StayTime'])."<br/>"
              ."<strong>Login IP: </strong>".$dat['LoginIP']."<br/>"
              ."<strong>Exit IP: </strong>".$dat['ExitIP']."<br/>"
              ."<strong>Computer Name: </strong>".$dat['HostName']."<br/>"
              .$msgLess

            ."</td>";
            $htmlSubmitted .= "<td>"
              ."Target Task: ".$dat['Target']."<br/>"
              ."Task Done: ".$dat['TotalTask']."<br/>"
              .$msg
            ."</td>";
            $htmlSubmitted .= "<td>".$dat['Report']."</td>";
            $htmlSubmitted .= "<td>".$dat['LoginLate']."</td>";
            $htmlSubmitted .= "<td>".$dat['ExitFast']."</td>";
            $htmlSubmitted .= "<td><span title='Verify' class='glyphicon glyphicon-ok hand' id='verify' data-id='".$dat['DailyReportID']."'></span>&nbsp;&nbsp;<span title='Reject' class='glyphicon glyphicon-remove hand' id='rejected' data-id='".$dat['DailyReportID']."'></span></td>";
            $htmlSubmitted .= "</tr>"; 
            $SubmittedReport++;
          }
          if($dat['IsPending'] == 0 && $dat['IsSubmitted'] == 1 && $dat['IsAccepted'] == 1){
            $htmlVerified .= "<tr>"; 
            $htmlVerified .= "<td>".$dat['ReportBy']."</td>";
            $htmlVerified .= "<td>".$dat['VerifiedByName']."</td>";
            $htmlVerified .= "<td>".$dat['VerifiedDate']."</td>";
            $htmlVerified .= "<td>".$dat['TotalTask']."</td>";
            $htmlVerified .= "<td>".$dat['Report']."</td>";
            $htmlVerified .= "</tr>";
          }
        }

        $LoggedInEmployeeRole = Yii::$app->session['Role'];
        if (strtolower($LoggedInEmployeeRole) == "admin" ||strtolower($LoggedInEmployeeRole) ==  "hr" || strtolower($LoggedInEmployeeRole) == "supervisor" || strtolower($LoggedInEmployeeRole) == "superadmin") {
          echo "<li><a data-toggle='tab' href='#verifyreport'>Verify Report&nbsp;<span class='badge'> ".$SubmittedReport."</span></a></li>";
          echo '<li><a data-toggle="tab" href="#verifiedReport">Verified Report</a></li>';
          echo '<li><a data-toggle="tab" href="#addEmployeeReportDate">Add Date</a></li>';
        } 

        $jsonFlag = json_decode($flags, true);
         ?>

         <li>
            <a data-toggle="tab" href="#attnReport">Attendance Report</a>
        </li>
  </ul>
  <div class="tab-content">
    <div id="dailyReport" class="tab-pane fade in active">
      <h2>Submit Report</h2>
      <div class="well">
          <div class="row" style="padding-bottom:8px;">
            <div class="col-lg-3" >
            <label for="totalTask">Total Task: </label>
            <input style="width:50%; " class="total-task" value="1" type="text" name="totaltask" id="totalTask">
            </div>
              <div class="col-lg-9">
                <label>You Stayed: <?= $staytime ?></label>
              </div>
          </div>
          <div class="row report-body" id="reportBody" style="overflow-y: scroll; height:250px;">
            <div class="form-group">
              <label for="report">Report</label>
            <textarea name="report" id="report" placeholder="Write Your Report...." class="report form-control" id="" cols="28" rows="3"></textarea>
            </div>
<?php if($jsonFlag['LoginFlag'] == 1){ ?>
            <div class="form-group">
              <label for="loginLate">Login Late (<?= "Late By: ".$jsonFlag['Late']?>)</label>
            <textarea name="loginlate" required="Please Fill This Area Also." class="login-late form-control" id="loginLate" placeholder="Write Your Reason for Login Late...." cols="28" rows="3"></textarea>
            </div>
<?php }
if($jsonFlag['ExitFlag'] == 1){
 ?>
            <div class="form-group">
              <label for="exitFast">Exit Fast (<?="Early By: ".$jsonFlag['Early']?>)</label>
            <textarea name="exitfast" required="Please Fill This Area Also." class="exti-fast form-control" id="exitFast" placeholder="Write Your Reason for Early Exit...." cols="28" rows="3"></textarea>
            </div>
        <?php } ?>
          </div>
            <div class="form-group" style=" height:25px; padding-right:10px;padding-top:10px;">              
              <button type="button" id="dailyReport" class="btn btn-success pull-right">Submit</button>
            </div>
      </div>
    </div>
    <div id="verifyreport" class="tab-pane fade">
      <h2>verifyreport</h2>
      <table id='verifyReport' class="table table-bordered">
                <thead>
                        <th width="4%">Employee Name</th>
                        <th width="15%">Attendance Information</th>
                        <th>Task Information</th>
                        <th width="35%">Report</th>
                        <th>Login Late</th>
                        <th>Exit Fast</th>
                        <th>Action</th>
                </thead>
                <tbody>
                    <?= $htmlSubmitted ?>
                </tbody>
              </table>
    </div>
    <div id="verifiedReport" class="tab-pane fade">
      <h2>verifiedReport</h2>
      <table id='verifiedReport' class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Submitted By</th>
                        <th scope="col">Verified By</th>
                        <th scope="col">Verified Date</th>
                        <th scope="col">Total Task</th>
                        <th scope="col">Report</th>
                    </tr>
                </thead>
                <tbody>
                  <?= $htmlVerified ?>
                </tbody>
              </table>
    </div>
    <div id="addEmployeeReportDate" class="add-employee-report-date tab-pane fade">
      <h2 align="center">Add Date</h2>
<?php 
  $html = "";
      foreach($employee as $emp){
        $html .= "<option value=".$emp['EmployeeID'].">".$emp['FullName']."</option>";
      }
   ?>

      <div class="row">
        <div class="col-md-12">
          <div class="col-md-4 well">
            <div class="form-group">
               <label for="employeeName">Employee Name</label>
               <select class="employeeID form-control select-single"  name="employeeid" >
                  <option value="0" disabled="true" selected="true">--Select Employee--</option>
                  <?= $html ?>
               </select>
            </div>
            <div class="form-group">
              <?php echo '<label>Date</label>';
                echo DatePicker::widget([
                        'name' => 'date', 
                        'value' => date('Y-m-d'),
                        'options' => ['placeholder' => 'Select from date ...'],
                        'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                        ]
                ]);
                ?>
            </div>
            <button type="button" class="btn btn-primary add-date" name="adddate" id="addDate">Add</button>
          </div>
        </div>
      </div>
    </div>
    <div id="attnReport" class="employee-report-attendance tab-pane fade">
      <p>Attendances</p>
      <?php $form = ActiveForm::begin(); ?>
      <div class="row well" style="padding:1px;">
        <div class="col-lg-12" style="padding:1px;" >
          <div class="col-lg-3" style="padding:1px;">
            <?php 
         $LoggedInEmployeeRole = Yii::$app->session['Role'];

        if(strtolower($LoggedInEmployeeRole) == "employee"||strtolower($LoggedInEmployeeRole) == "trainee"):
          echo '<h3 id="currentLoggedIn" data-employeeID='.Yii::$app->session['EmployeeID'].'>'.Yii::$app->session['FullName'].'</h3>';
        else:
            $model->UserID=Yii::$app->user->id;  
            echo $form->field($model, 'UserID')->widget(Select2::classname(), [
                       'data' => Yii::$app->empList->listEmployee(),
                       'language' => 'en',
                       'options' => ['placeholder' => 'Select Employee  ...'],
                       'pluginOptions' => [
                               'allowClear' => true
                            ],
               ])->label("Employee Name");
        endif;
       ?>
          </div>
          <div class="col-lg-3">
            <?php
            echo '<label>From</label>';
                echo DatePicker::widget([
                        'name' => 'from', 
                        'value' => date('Y-m-d', strtotime('first day of this month')),
                        'options' => ['placeholder' => 'Select from date ...'],
                        'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                        ]
                ]);
                ?>
          </div>
          <div class="col-lg-3">
            <?php
            echo '<label align="center">To</label>';
                echo DatePicker::widget([
                        'name' => 'to', 
                        'value' => date('Y-m-d'),
                        'options' => ['placeholder' => 'Select to date'],
                        'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                        ]
                ]);
                ?>
          </div>
          <div class="col-lg-3" style="padding-top:20px;">
            <?php echo Html::button(' Find', ['data-employeeID'=>Yii::$app->session['EmployeeID'], 'class' => 'btn btn-primary report-go glyphicon glyphicon-search', 'data-id'=>'findo']); ?>
     <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
      <table id="tableReport" class="table table-bordered">
        <thead>
          <th width="15%">Date</th>
          <th width="15%">Login Info</th>
          <th width="15%">Exit Info</th>
          <th width="15%">Stay Time</th>
          <th width="35%">Report</th>
          <th width="5%">Total Task</th>
        </thead>
        <tbody>
          <!-- reports will be displayed here -->
        </tbody>
      </table>
    </div>
  </div>
</div>


<!--Start of modal-->
<div id="reportReject" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Remark
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class = "remarks"></span>
                <textarea class="form-control" rows="5" id="remarks"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveRemark" data-id="0">Save </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--End Of Modal-->
<?php 
function timeToSec($string){
      list($hour, $min, $sec) =array_pad(explode(':', $string, 3), -3, NULL);
      return $hour*3600+$min*60+$sec;
    }
 function getTime($duration) {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        $seconds = $duration % 60;
    return "$hours:$minutes:$seconds";
    }

    function gethms($duration) {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        return "$hours Hrs $minutes Min";
    }
 ?>
<?php
$script = <<<JS
if (document.URL.indexOf("?tab=report") > 0) {
  $('a[href="#verifyreport"]').trigger('click');
}
if (document.URL.indexOf("?tab=addEmployeeReportDate") > 0) {
  $('a[href="#addEmployeeReportDate"]').trigger('click');
}

var ele = $('div#dailyReportContainer');

ele.find('select[name="employeeid"]').select2();

  var from = ele.find('div#attnReport input[name="from"]').val();
  var to = ele.find('div#attnReport input[name="to"]').val();
  var employeeid = ele.find('div#attnReport select[name="Dailyreport[UserID]"]').val();
  var employeeidnormal = ele.find('div#attnReport h3#currentLoggedIn').attr('data-employeeid');
  GetReport(employeeid, employeeidnormal, from, to);

ele.find('div.add-employee-report-date button.add-date').click(function() {
        var id = ele.find('select[name="employeeid"] option:selected').val();
        var date=$('input[name="date"]').val();
         AttendanceDate(id,date);
      
    }); 
     function AttendanceDate(empid,date){
           $.ajax({
              type: "POST",
              url: "adddate",
              data: {
                    "EmployeeID": empid,
                    "Date": date
              },
              dataType: 'json',
              cache: false,
              success: function(data) {
               // if(data.result == true){showMessage(data.message);}else{showError(data.message);}
              },
              error: function(data) {
               
              }
             });
        
     }      


        $('button#dailyReport').on('click', function() {
            var totaltask = $('input[name="totaltask"]').val();
            var report = $('textarea[name="report"]').val();
            var loginlate = $('textarea[name="loginlate"]').val();
            var exitfast = $('textarea[name="exitfast"]').val();

            if (confirm("Do You Want To Continue?")) {
                saveData(totaltask, report, loginlate, exitfast);
            } else {
                showMessage("Cancled Report Submission.");
            }
            
        });

        function saveData(totaltask, report, loginlate, exitfast) {
            $.ajax({
              type: "POST",
              url: "savetask",
              data: {
                    "totaltask": totaltask,
                    "report": report,
                    "loginlate": loginlate || "No Late Login",
                    "exitfast": exitfast || "No Exit Fast"
              },
              dataType: 'json',
              cache: false,
              success: function(data) {
               if(data.result == true){showMessage(data.message);}else{showError(data.message);}
              },
              error: function(data) {
               
              }
             });
        }

        $('button#report').click(function() {
            var empid = $('select#employee-employeeid').val();
            var date = $('input#w1').val();
            Date(empid, date);

        });

        function Date(empid, date, identity) {
            $.ajax({
                url: 'adddate',
                type: 'post',
                data: {
                    empid: empid,
                    date: date
                },
                success: function(data) {}
            });

        }
        $('span#verify').click(function() {
            var identity = $('span#verify').attr('data-id');
           ReportUpdate(true, '', identity);
        });

        $('span#rejected').click(function() {
            $('div#reportReject').modal();
            $('button#saveRemark').click(function() {
                var remarks = $('textarea#remarks').val();
                var identity = $('table#verifyReport span#rejected').attr('data-id');
                var length = remarks.length;
                if (length < 15) {
                    $('span.remarks').text("Remarks Not Enough");

                } else {
                    ReportUpdate(false, remarks, identity);
                    $('div#reportReject').modal('hide');
                }
            });
        });

        function ReportUpdate(status, remarks, identity) {
            $.ajax({
              type: "POST",
              url: "approvereport",
              data: {
                    "Status": status,
                    "Remarks": remarks,
                    "ReportID": identity,
              },
              dataType: 'json',
              cache: false,
              success: function(data) {
               
              },
              error: function(data) {
               
              }
             });
        }
ele.find('div#attnReport button.report-go').click(function() {
       var from = ele.find('div#attnReport input[name="from"]').val();
       var to = ele.find('div#attnReport input[name="to"]').val();
       var employeeid = ele.find('div#attnReport select[name="Dailyreport[UserID]"]').val();
       var employeeidnormal = ele.find('div#attnReport h3#currentLoggedIn').attr('data-employeeid');
       GetReport(employeeid, employeeidnormal, from, to);
});

  function GetReport(employeeid, employeeidnormal, from, to){
    $.ajax({
        type: "POST",
        url: "getreport",
        data: {
            "EmployeeID": employeeid || employeeidnormal,
            "From": from,
            "To": to,
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
          ele.find('div#attnReport table#tableReport tbody').empty();
          ele.find('div#attnReport table#tableReport tbody').append(data.html);
        },
        error: function() {
         
        }
       });
  }
JS;
$this->registerJs($script);
?>

 <?php $this->registerCSS("
   .remarks{
    color:red;
   }
   .danger-text{
    color:#d9534f;
   }
   textarea {
   resize: none;
}
 ");?>