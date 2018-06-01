<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;

use kartik\select2\Select2;
use kartik\date\DatePicker;

use backend\modules\user\models\Role;
use backend\modules\leave\models\leave;
use backend\modules\user\models\Employee;
use backend\modules\leave\models\Employeeleave;
?>

<?php if (Yii::$app->session->hasFlash('leaverequested')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Saved!</h4>
    <?= Yii::$app->session->getFlash('leaverequested') ?>
  </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('leaverequestedcancle')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Cancelled!</h4>
    <?= Yii::$app->session->getFlash('leaverequestedcancle') ?>
  </div>
<?php endif; ?>

<?php 
$this->title = "Request Leave";
 ?>

  <?php if (strtolower(Yii::$app->session['Role'])== 'admin' || strtolower(Yii::$app->session['Role'])== 'supervisor') { ?>
    <?= Html::a('Requested Leave '. Html::tag('span', $CountLeaveRequest, ['class' => 'badge']),['/leave/leave/approve'], ['class' => 'btn btn-primary']) ?>
 <?php } ?>

<br/><br/>

<div class="container">
   <div class="row">
      <ul id="tabList" class="nav nav-tabs">
         <li class="active">
            <span class="item-tab hand" data-id="containerLeaveRequest">Leave Request
            </span>
         </li>
         <li>
            <span class="item-tab hand" data-id="containerLeaveDetails">Leave Details
            </span>
         </li>
      </ul>
      <div id="tabContainer" class="tab-content">
         <div id="containerLeaveRequest" class="tab-pane fade in active" data-active="leaverequest">
            <h3>Leave Request</h3>
            <div class="row">
            	<div class="col-lg-12">
            		<div class="col-lg-3 well">
            				<!-- foreach leave type -->
            				<?php 
                    $Val1 = 0; $Val2 = 0;
                    // echo "<pre>"; print_r($Result); die();
                    foreach ($Result as $key => $Res) {
	                        $Val1 = $Res['Earned'];
	                        $Val2 = $Res['Balance'];
	                        $Blc = $Val1-$Val2;
	                        echo "<div class='well leaveInfo'>LeaveType: ".$Res['Title']."
	                        <p>Earned: ".$Val1."</p>
	                        <p>Total Balance: ".$Val2."</p>
	                        <p>Taken: ".$Blc."</p>
	                        </div>";
                        }
                        ?>
            				<!-- end for each  -->
            		</div>
            		<div class="col-lg-9">
            			<div class="well">
            				<div class="row">
              <?php $form = ActiveForm::begin(); ?>
              <div class="col-lg-12">
                  <div class="col-lg-5">
<?php 
	//check for login employee role
	$LoggedInEmpRole = strtolower(Yii::$app->session['Role']);
		if($LoggedInEmpRole == 'supervisor' || $LoggedInEmpRole == 'admin'){
			$EmployeeLeaveModel->EmployeeID = Yii::$app->session['UserID'];
				echo $form->field($EmployeeLeaveModel, 'EmployeeID')->widget(Select2::classname(), [
					'data' => Yii::$app->empList->listEmployee(),
					'language' => 'en',
					'options' => ['placeholder' => 'Select Employee  ...'],
					'pluginOptions' => [
						'allowClear' => true
					],
				])->label("Employee Name");
		}else{?>
     <?=  $form->field($EmployeeLeaveModel, 'EmployeeID')->hiddenInput(['maxlength' => true ,'readonly' => true, 'value'=>Yii::$app->session['EmployeeID']])->label(false);?>

     <?php

			echo "<h4><label>Name:</label>".' '.Yii::$app->session['FullName']."</h4>";
		}
?>
                  </div>
                  <div class="col-lg-5">
                    <label class="control-label" for="employeeleave-employeeid">Leave Type</label>
                  <select id="employeeleave-leavetypeid" class="form-control" name="Employeeleave[LeaveID]">
                  </select>
                  </div>
                  <div class="col-lg-2">
                    <label class="control-label" for="daysleft">Days Left</label><br/>
                    <span id="daysleft"></span>
                  </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-5">
                   <?= $form->field($EmployeeLeaveModel, 'From')->label('From')->widget(DatePicker::classname(), [
                              'options' => ['placeholder' => 'yyyy-mm-dd',],
                               'pluginOptions' => [
                                   'format' => 'yyyy-mm-dd',
                                  'todayHighlight' => true,
                                   'autoclose'=>true,
                                  'startDate' => "0d"
                                ]
                              ]);  ?>
                </div>
                <div class="col-lg-5">
                  <?= $form->field($EmployeeLeaveModel, 'To')->label('To')->widget(DatePicker::classname(), [
                              'options' => ['placeholder' => 'yyyy-mm-dd',],
                               'pluginOptions' => [
                                   'format' => 'yyyy-mm-dd',
                                  'todayHighlight' => true,
                                   'autoclose'=>true,
                                  'startDate' => "0d"
                              ]
                              ]);  ?>
                </div>
                <div class="col-lg-2">
                   <?= $form->field($EmployeeLeaveModel, 'NoOfDays')->textInput(['maxlength' => true ,'readonly' => true]) ?>
                   <input type="hidden" name="balance" value="Norway">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <?= $form->field($EmployeeLeaveModel, 'Reason')->textarea(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                  <?= $form->field($EmployeeLeaveModel, 'File')->fileInput(['maxlength' => true]) ?>
                </div>
              </div>
              
              <?= Html::submitButton('Apply', ['class'=> 'btn btn-success', 'data-id'=>Yii::$app->session['EmployeeID'], 'employee-id'=>Yii::$app->session['EmployeeID']]) ;?>

            <?php ActiveForm::end(); ?>
            </div>
            			</div>
            		</div>
            	</div>
            </div>
         </div>

         <div id="containerLeaveDetails" class="tab-pane fade" data-active="leavedetails">
            <h3>Leave Details</h3>
            <table class="table table-bordered">
    <thead>
      <tr>
        <th>Leavetype</th>
        <th>Reason</th>
        <th>DateRange</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ResultLeave as $key => $Leave) :?> 

      <tr>
        <td><?= ucfirst($Leave['LeaveType'])  ?></td>
        <td><?= ucfirst($Leave['Reason'])  ?></td>
        <td><?= $Leave['DateRange'] ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
         </div>
      </div>
   </div>
</div>


<?php 
$js = <<< JS

//for tabs
$(".nav-tabs span").click(function() {
  $(this).tab("show");
});

$("ul#tabList").find("li span").click(function() {
  var ele = $("div#tabContainer");
  ele.find("div.tab-pane").removeClass("in active");
  var current = $(this).attr("data-id");
  ele.find("div#" + current).addClass("in active");
});
//end tabs jquery

//for no of days calculation from from and to dates
$('div#containerLeaveRequest').find('input[name="Employeeleave[To]"]').on('change', function() {
 var FromDate = new Date($('div#containerLeaveRequest').find('input[name="Employeeleave[From]"]').val());
 var ToDate = new Date($('div#containerLeaveRequest').find('input[name="Employeeleave[To]"]').val());
 if (ToDate < FromDate) {
  alert("Cannot Choose Less Date from" + FromDate);
  $('div#containerLeaveRequest').find('input[name="Employeeleave[To]"]').val('');
 } else {

  var DiffDays = Difference(FromDate, ToDate) + 1;

  $('div#containerLeaveRequest').find('input[name="Employeeleave[NoOfDays]"]').val(DiffDays);
 }

 function Difference(a, b) {
  var PerDay = 1000 * 60 * 60 * 24;
  var UTC1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
  var UTC2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
  return Math.floor((UTC2 - UTC1) / PerDay);
 }
});

//end of no of days calculation

//check for apply only or apply and approve
  $('div#containerLeaveRequest').find('select[name="Employeeleave[EmployeeID]"]').on('change', function(){
      var EmployeeID = $('div#containerLeaveRequest').find('select[name="Employeeleave[EmployeeID]"]').val();
      var LoggedInEmpID = $('div#containerLeaveRequest').find('button').attr('employee-id');
      if(EmployeeID == LoggedInEmpID){
      	$('div#containerLeaveRequest').find('button').text("Apply");
      }else{
      	$('div#containerLeaveRequest').find('button').text("Apply And Approve");

      }
  });

  $('#employeeleave-employeeid').on('select2:select', function (e) {
        var data = e.params.data.id;
        $('div#containerLeaveRequest').find('button').attr('data-id',data);

  //fetch employee leavetypes

        GetEmployeeLeave(data);

});

$(document).ready(function(){
var val =  $('div#containerLeaveRequest').find('button').attr('employee-id');
  GetEmployeeLeave(val);

  var Balance = $("div#containerLeaveRequest").find('select#employeeleave-leavetypeid option:selected').attr('data-balance');
  $("div#containerLeaveRequest").find('span#daysleft').text(Balance);
});



//ajax to fetch leavetypes
  function GetEmployeeLeave(data){
    $.ajax({
          type: "POST",
          url: "leavetypelist",
          data:{
            data:data
          },
          dataType:'json',
          cache: false,
          success: function(data) {
            console.log(data);
            var option='<option value="0">Select LeaveType</option>';
            for(i=0;i<data.length;i++)
            {
              option+='<option data-leavetype= "'+data[i].LeaveTypeID+'" data-balance="'+data[i].Balance+'" value="'+data[i].LeaveID+'">'+data[i].Title+'</option>';
            }

            $('div#containerLeaveRequest').find('select[name="Employeeleave[LeaveID]"]').html(option);
            
          }
        });
  }
  //show balance leave for particular leave type of any selected employee

$("div#containerLeaveRequest").find('select#employeeleave-leavetypeid').change(function(){
    var Balance = $("div#containerLeaveRequest").find('select#employeeleave-leavetypeid option:selected').attr('data-balance');
    // console.log(Balance);
    $("div#containerLeaveRequest").find('span#daysleft').text(Balance);

});

JS;

$this->registerJS($js);
 ?>

 <?php 
   $this->registerCss("
.nav-tabs > li > span {
       margin-right: 2px;
       line-height: 1.42857143;
       border: 1px solid transparent;
       border-radius: 4px 4px 0 0;
   }
   
   .nav > li > span {
       position: relative;
       display: block;
       padding: 10px 15px;
   }

   .nav {
    list-style: none;
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
   

   .nav-tabs > li.active > span, .nav-tabs > li.active > span:focus, .nav-tabs > li.active > span:hover {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: rgb(221, 221, 221);
    border-bottom-color: transparent;

.leaveInfo{
   border: 2px solid;
}
     ");
    ?>