<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

use backend\modules\user\models\Role;
use backend\modules\user\models\Employee;
use backend\modules\dailyreport\models\Dailyreport;
use yii\helpers\ArrayHelper;

use kartik\date\DatePicker;
use kartik\select2\Select2;

$this->title = "Daily Report";

/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\Holiday */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container">
    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#dailyReport">Daily Report</a></li>
     <?php 
           $LoggedInEmployeeRole = Yii::$app->session['Role'];
        if(strtolower($LoggedInEmployeeRole) == "admin"||strtolower($LoggedInEmployeeRole) == "hr"||strtolower($LoggedInEmployeeRole) == "supervisor" ||strtolower($LoggedInEmployeeRole) == "superadmin"):
            echo "<li><a data-toggle='tab' href='#verifyreport'>Verify Report<span class='badge'> ".$countreport."</span></a></li>";
                echo '<li><a data-toggle="tab" href="#verifiedReport">Verified Report</a></li>';
                echo '<li><a data-toggle="tab" href="#addDate">Add Date</a></li>';
             else:
        ?>
    <?php endif;  ?>
   </ul>

  <div class="tab-content">
    <div id="dailyReport" class="tab-pane fade in active">
          <?php $form = ActiveForm::begin(); ?>
             <div class="col-lg-5">
              <?php foreach ($emp as $Emp):
              if($Emp['IsPending']==1 && $Emp['CreatedBy']==Yii::$app->session['UserID']):  ?>
             <?= $form->field($model, 'CreatedTime')->widget(Select2::classname(), [
					'data' => ArrayHelper::map(Dailyreport::find()->where(['IsPending'=>1])->orderBy(['CreatedTime'=>SORT_DESC])->all(), 'DailyReportID', 'CreatedTime'),
					'language' => 'en',
					'options' => ['placeholder' => 'Select Date  ...'],
					'pluginOptions' => [
						'allowClear' => true
					],
				])->label("Yesterdays Report");
                  ?>
                 <?php endif; ?>
                 <?php endforeach; ?>
              </div>
      <div class="well">
      
          <div id="reportSave">
          <span id="date" data-id=<?=$CurrentEmployeeID['DailyReportID']?>>Date:<?=date('Y-m-d')?></span>
          </div>
         <label>Total Task</label>
         <input type="text" class="form-control" id="task" >
                <?= $form->field($model, 'Report')->widget(TinyMce::className(), [
                                'options' => ['rows' => 3],
                                'language' => 'en',
                                'clientOptions' => [
                                    'plugins' => [
                                        "advlist autolink lists link charmap print preview anchor",
                                        "searchreplace visualblocks code fullscreen",
                                        "insertdatetime media table contextmenu paste"
                                    ],
                                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                                ]
                 ]);?>

        <button type="button" id="dailyReport" class="btn btn-success">Save</button>
    </div>
        <div class=" well submittedReport">
            <label>My Report</label> 
               <table id='myreport' class="table table-bordered">
              <thead>
                <tr>
                   
                  <th scope="col">Submitted Date</th>
                  <th scope="col">Total Task Done</th>
                  <th scope="col">Report</th>
                  <th scope="col">VerifiedBy</th> 
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($emp as $Emp):?>
                  <?php if($Emp['IsVerified']==1 && $Emp['CreatedBy']==Yii::$app->session['UserID'] ):?>
                    <tr>
                        <td><?=$Emp['CreatedTime']?></td>
                        <td><?=$Emp['TotalTask']?></td>
                        <td><?=$Emp['Report']?></td>
                        <td><?=$Emp['EmpName']?></td>
                    </tr>
                   <?php endif;?>   
                  <?php endforeach;?>
              </tbody>
            </table>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
      
    <div id="verifyreport" class="tab-pane fade">
      <table id='verifyReport' class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">Submitted By</th>
                  <th scope="col">Submitted Date</th>
                  <th scope="col">Total Task Done</th>
                  <th scope="col">Report</th>
                  <th scope="col">Action</th>
                 </tr>
              </thead>
              <tbody>
                  <?php foreach ($emp as $Emp):?>
                  <?php if($Emp['IsVerified']==0 && $Emp['IsPending']==0):?>
                    <tr>
                        <td><?=$Emp['UName']?></td>
                        <td><?=$Emp['CreatedTime']?></td>
                        <td><?=$Emp['TotalTask']?></td>
                        <td><?=$Emp['Report']?></td>
                        <td><span class="glyphicon glyphicon-ok hand" id="verify" data-id=<?=$Emp['DailyReportID']?>></span>
                      <span class="glyphicon glyphicon-remove hand" id="rejected" data-id=<?=$Emp['DailyReportID']?>></span></td>
                      </tr>
                   <?php endif;?>   
                  <?php endforeach;?>
              </tbody>
            </table>
    </div>
    <div id="verifiedReport" class="tab-pane fade">
              
            <table id='verifiedReport' class="table table-bordered">
              <thead>
                  <tr>
                    <th scope="col">Verified By</th>
                    <th scope="col">Verified Date</th>
                    <th scope="col">Report</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($emp as $Emp):?>
                   <?php if($Emp['IsVerified']==1):?>
                    <tr>
                     <td><?=$Emp['EmpName']?></td>
                     <td><?=$Emp['VerifiedDate']?></td>
                     <td><?=$Emp['Report']?></td>
                        
                     </tr>
                   <?php endif;?>  
                  <?php endforeach;?>
              </tbody>
            </table>
        </div>
      <div id="addDate" class="tab-pane fade">
          <div class=" row reportDate" style="margin-top: 10px">
              <div class="col-lg-6">
             <?= $form->field($Employee, 'EmployeeID')->widget(Select2::classname(), [
					'data' => Yii::$app->empList->listEmployee(),
					'language' => 'en',
					'options' => ['placeholder' => 'Select Employee  ...'],
					'pluginOptions' => [
						'allowClear' => true
					],
				])->label("Employee Name");
                  ?>
              </div>
              <div class="col-lg-4">
                    <?php
            echo '<label>Date</label>';
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
           </div>   
          <button type="button" class="btn btn-primary" id="report">Add </button>
        </div>
      </div>
         
</div>
<!--Start of modal-->
<div id="reportReject" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Remark</h5>
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
$script=<<<JS
        if(document.URL.indexOf("?tab=report")>0){
        $('a[href="#verifyreport"]').trigger('click');
        }
           $('#dailyreport-createdtime').on('change', function() {
      var date = $("#dailyreport-createdtime option:selected").text();
      var submittedDate= $('div#reportSave').find('span#date').text(date);
                  
    });

          $(".select-single").select2();
        
 $('span#verify').click(function() {
        var identity = $('span#verify').attr('data-id');
        ReportUpdate(true,'',identity,function(){
            });
 });
        
  $('span#rejected').click(function() {
        $('div#reportReject').modal();
             $('button#saveRemark').click(function() {
                    var remarks=$('textarea#remarks').val();
                    var identity = $('table span').attr('data-id');
                    var length=remarks.length;  
                       
                   if(length<7){
                         $('span.remarks').text("Remarks Not Enough");
                   
                   }else{
                     ReportUpdate(false,remarks,identity,function(){
                });
                     $('div#reportReject').modal('hide');
                    }
            });    
    });
       
   $('button#dailyReport').on('click',function(){
        var totaltask=$('input#task').val();
        var report=tinyMCE.get('dailyreport-report').getContent();
        var submittedDate= $('div#reportSave').find('span#date').text();
        var user=$('div#reportSave').find('span#date').attr('data-id');
        saveData(totaltask,report,submittedDate,user);
    });   
        
          function saveData(totaltask,report,submittedDate,user){
           $.ajax({
           url: 'savetask',
           type: 'post',
           data: {
               totaltask:totaltask,
               report:report,
               submittedDate:submittedDate,
               user:user
            },
           success: function (data) {
          
                   }
          });
        
     }
        
     $('button#report').click(function() {
        var empid=$('select#employee-employeeid').val();
        var date=$('input#w1').val();
         Date(empid,date);
      
    }); 
     function Date(empid,date,identity){
           $.ajax({
           url: 'adddate',
           type: 'post',
           data: {empid:empid,date:date},
           success: function (data) {
                   }
          });
        
     }      
    
 function ReportUpdate(status,remarks,identity,callMe){
           $.ajax({
           url: 'approvereport',
           type: 'post',
           data: {status:status,remarks:remarks,identity:identity},
           success: function (data) {
       
                     }
          });
 	callMe();
    }
        
        
 
JS;
$this->registerJs($script);
?>

 <?php $this->registerCSS("
 
 .remarks{
  color:red;
 }
 "); ?>