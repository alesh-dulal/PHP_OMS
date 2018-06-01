<?php
$this->title = 'OMS';
$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);

use kartik\select2\Select2;
use backend\modules\user\models\Employee;
use dosamigos\tinymce\TinyMce;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php if (Yii::$app->session->hasFlash('LoginSuccess')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('LoginSuccess') ?>
  </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('ReportSubmitSuccess')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('ReportSubmitSuccess') ?>
  </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('ForgotReportSubmission')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('ForgotReportSubmission') ?>
  </div>
<?php endif; ?>

<!-- ======find currently logged in employee role===== -->
  <?php $role = strtolower(Yii::$app->session['Role']);?>
<!-- =====end===== -->
  <div id="dashboard" class="index">
  <div class="row">
    <!-- =====show to every staff employee's todays punchin time====== -->
     <div id="checkInTime" class="hide text-center">
        <p><strong>Todays Punchin Time : </strong> <span class="checkin-time"></span>
           <strong>|</strong>
           <strong id="reportToVerify">Report to be Verified: 
            <span><?php echo ($CountSubmittedReport);?></span> 
          </strong> 
        </p>
     </div>
  <!-- =====end of show to every staff employee's todays punchin time===== -->

  <!-- =====show to every staff calender, born today and off today===== -->
     <div class="col-lg-6" >
        <div class="col-lg-12 well ">
           <h4>Calender</h4>
           <?= \yii2fullcalendar\yii2fullcalendar::widget(array('events'=> $events));?>
        </div>

        <div class="col-lg-12 well ">
           <h4>Who is Off-time today?</h4>
           <?php  foreach ($LeaveToday as $OffTime):?>
            <?=($OffTime['Name']);?><br>
           <?php endforeach;?>
        </div>

        <div class="col-lg-12 well ">
           <h4>Born Today</h4>
           <?php  foreach ($BornToday as $Born):?>
           <strong><?=($Born['FullName']);?></strong>
           <?php endforeach;?>
        </div>
     </div>
  <!-- =====end of show to every staff calender, born today and off today===== -->

     <div class="col-lg-6 content">
      <!-- =====employees todays attendance show to HR, admin, supervisor only hide to other staffs===== -->
      <?php if  ($role == 'admin' || $role == 'hr' ||$role == 'supervisor'){ ?>
        <div class="col-lg-12 well present_today_main" id="filterEmployee">
           <ul id="tabList" class="nav nav-tabs">
              <li class="active test">
                 <span data-toggle="tab" class="item-tab hand" data-id="containerActive">Active
                   <span class="badge">
                   <?= count($EmployeeTodayAttn); ?>
                   </span>
                 </span>
              </li>
              <li class="test">
                 <span data-toggle="tab" class="item-tab hand" data-id="containerPresent">Present
                   <span class="badge">
                     <?php $i = 0; ?>
                       <?php foreach ($EmployeeTodayAttn as $key => $PresentToday): ?>         
                        <?php if ($PresentToday['Status'] == 'Present'){$i++;} ?> 
                       <?php endforeach?>
                     <?php echo $i; ?>
                   </span>
                 </span>
              </li>
              <li class="test">
                 <span data-toggle="tab" class="item-tab hand" data-id="containerAbsent">Absent
                   <span class="badge">
                   <?php $j = 0; ?>
                     <?php foreach ($EmployeeTodayAttn as $key => $PresentToday): ?>         
                      <?php if ($PresentToday['Status'] == 'Absend'){ $j++;} ?> 
                     <?php endforeach?>
                   <?php echo $j; ?>
                   </span>
                 </span>
              </li>
              <li class="dropdown">
                  <select class="form-control">
                    <option value="all">-- All --</option>
                    <?php foreach ($departmentLists as $key => $departmentList) {?>
                      <option value="<?= $departmentList['ListItemID'] ?>"><?= $departmentList['Title'] ?></option>
                   <?php } ?>
                  </select>
              </li> 
           </ul>
           <div id="tabContainer" class="tab-content">
              <div id="containerActive" class="filter tab-pane fade in active" data-active="active">
                 <div class="presentToday">
                    <table class="table">
                       <thead>
                          <tr>
                             <th>Name</th>
                             <th>Time</th>
                          </tr>
                       </thead>
                       <tbody>
                       <?php foreach ($EmployeeTodayAttn as $key => $PresentToday): ?>
                          <tr>
                             <td class= "take" dept-id ="<?= $PresentToday['DepartmentID']; ?>"><?= $PresentToday['FullName']; ?></td>
                             <td><?= $PresentToday['Attendance']; ?></td>
                          </tr>
                       <?php endforeach?>
                       </tbody>
                    </table>
                 </div>
              </div>
              <div id="containerPresent" class="filter tab-pane fade in" data-active="present">
                 <div class="presentToday">
                          <!-- ======donot show the table if no one is present====== -->
                    <table class="table">
                       <thead>
                          <tr>
                             <th>Name</th>
                             <th>Time</th>
                          </tr>
                       </thead>
                         <tbody>
                       <?php foreach ($EmployeeTodayAttn as $key => $PresentToday): ?>
                       <?php if ($PresentToday['Status'] == 'Present') { ?>
                            <tr>
                               <td class= "take" dept-id ="<?= $PresentToday['DepartmentID']; ?>"><?= $PresentToday['FullName']; ?></td>
                               <td><?= $PresentToday['Attendance']; ?></td>
                            </tr>
                       <?php } ?>
                       <?php endforeach ?>
                         </tbody>
                    </table>

                 </div>
              </div>
              <div id="containerAbsent" class="filter tab-pane fade in" data-active="absent">
                 <div class="presentToday">
                    <table class="table">
                       <thead>
                          <tr>
                             <th>Name</th>
                          </tr>
                       </thead>
                           <tbody>
                       <?php foreach ($EmployeeTodayAttn as $key => $PresentToday): ?>
                         <?php if ($PresentToday['Status'] == 'Absend') {?>
                              <tr>
                                 <td class= "take" dept-id ="<?= $PresentToday['DepartmentID']; ?>"><?= $PresentToday['FullName']; ?></td>
                              </tr>
                         <?php } ?>
                       <?php endforeach ?>
                           </tbody>
                    </table>
                 </div>
              </div>
           </div>
        </div>
        <?php } ?>
        <!-- =====end of hide to other except hr admin supervisor===== -->
        <!-- =====create post show to HR and admin only====== -->
        <?php if($role == 'hr' ||$role == 'admin'){ ?>
        <div class="col-lg-12 well ">
           <h4> Create Post</h4>
           <ul class="nav nav-tabs status-post">
              <li class="active" data-type="events"><span class="hand">Event</span></li>
              <li data-type="appretiations"><span class="hand">Appreciation</span></li>
              <li data-type="Comments"><span class="hand">Comment</span></li>
              <li data-type="Awards" ><span class="hand">Award</span></li>
           </ul>
           <div class="">
              <?php $form = ActiveForm::begin(); ?>
              <input type="text" class="form-control" id="title" placeholder="Title here" style="margin-top: 10px">
              <?= $form->field($model, 'Description')->widget(TinyMce::className(), [
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
              <div class="pull-left" style="margin-top: 10px">
              </div>
              <button type="button" class="btn btn-success btn-lg btn-block post">Post</button>
              <?php ActiveForm::end(); ?>    
           </div>
        </div>
        <?php } ?>
<!-- =====end of show to HR and admin only=====  -->
<!-- =====show to every employee=====  -->
        <div class="col-lg-12 well" >
           <?php foreach($PostStatus as $status):  ?> 
           <div class="panel panel-default single-post">
              <div class="panel-heading"><?=strtoupper($status->Type)?></div>
              <div class="panel-body">
                 <h5>
                  <strong>
                    <?php $user=Employee::find(['UserID','FullName'])->where(['UserID'=>$status->InsertedBy])->one();
                      echo $user->FullName?>
                  </strong>
                  <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                      <?=$status->Title;?><br>
                  <span style="font-size: 11px;">
                      <?php
                       echo ($status->InsertedDate);?>
                  </span>
                 </h5>
                 <p><?=$status->Description?></p>
              </div>
           </div>
           <?php endforeach; ?>
        </div>
<!-- =====end of show to every employee===== -->
     </div>
  </div>
</div>


<?php
$script=<<< JS
$("ul#tabList").find("li span").click(function() {
  var ele = $("div#tabContainer");
  ele.find("div.tab-pane").removeClass("in active");
  var current = $(this).attr("data-id");
  ele.find("div#" + current).addClass("in active");
});

$('div#dashboard').find('ul.status-post li').click(function() {
  $(this).siblings().removeClass('active');
  $(this).addClass('active');
});

$('button.post').click(function() {
  var title = $('input#title').val();
  var description = tinyMCE.get('poststatus-description').getContent();
  //var employee=$('#w2').val();
  var type = $('div#dashboard').find('ul.status-post li.active').attr("data-type");
  loadpost(title, description, type);
});

function loadpost(title, description, type) {
  $.ajax({
    url: 'default/savepost',
    type: 'post',
    data: {
      title: title,
      description: description,
      type: type
    },
    success: function(data) {
      
    }
  });
}

loadLoginTime();

function loadLoginTime() {
  var e = $('div#dashboard').find('div#checkInTime span.checkin-time');
  e.parents('div#checkInTime').addClass('hidse');
  if(e.text().trim().length == 0) {
    $.ajax({
      url: 'default/todaylog',
      type: 'post',
      success: function(data) {
        if(data.length > 0) {
          e.parents('div#checkInTime').removeClass('hide');
          e.text(data);
        }
      }
    });
  }
}
$('div#dashboard').find('ul#tabList > li > select').on('change', function()
  {
    $('div#tabContainer').find('div.filter table tbody tr').show();
    var DeptID = $('div#dashboard').find('ul#tabList li select option:selected').val();
    if(DeptID == "all")
      {
        $('div#tabContainer').find('div.filter table tbody tr').show();
      }
      else
      {
        $('div#tabContainer div.filter table > tbody > tr').each(function()
        {
          var dept = $(this).find('td.take').attr('dept-id');
            if(dept != DeptID)
            {
              $(this).hide();
            }
        });
      }
      //count rows to show in the badge
      var rowCount = [];
      var i=0;
      $('div#filterEmployee').find('div.filter table').each(function(){
        var numOfVisibleRows = $(this).find('tbody tr').filter(function() {
            return $(this).css('display') !== 'none';
          }).length;
         rowCount.push(numOfVisibleRows);
      });
      $.each(rowCount, function(){
        $('ul#tabList').find('li.test').each(function(i){
          $(this).find('span.badge').text(rowCount[i]);
        });
      });

  });


JS;
$this->registerJs($script);
?> 
<?php 
  $this->registerCSS("
#reportToVerify{
    color:red;
}
    .presentToday{
    max-height: 300px;
    overflow-y:scroll; 
}

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

  .nav-tabs > li.active > span {
     background-color: transparent;
      color: #337ab7;
      text-decoration: none;
  }
   
   .nav-tabs > li.active > span {
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
  }
  ul#tabList > li > select{
    width:8em;
  }
    ");
 ?>     