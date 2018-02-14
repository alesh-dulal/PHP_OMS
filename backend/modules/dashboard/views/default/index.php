<?php

/* @var $this yii\web\View */

$this->title = 'OMS';
$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);

use kartik\select2\Select2;
use backend\modules\user\models\Employee;
use dosamigos\tinymce\TinyMce;
use yii\widgets\ActiveForm;
?>
<div id="dashboard" class="index">
    <div class="row">
      <div id="checkInTime" class="hide text-center">
        <p><strong>Todays Punchin Time : </strong> <span class="checkin-time"></span></p>
      </div>

    <div class="col-lg-6" >

                    <div class="col-lg-12 well ">
                             <h4>Calender</h4>
                           <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
                           'events'=> $events,
                              ));
                           ?>
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
    
    
            <div class="col-lg-6 content">            
                    <?php $rol=strtolower(Yii::$app->session['Role']);?>
                        <div class="col-lg-12 well present_today_main">
                          <h6 align="center">Employee Today's Attendance Status</h6>
                          <div class="presentToday">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Date</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                                <?php if  ($rol== 'admin' || $rol== 'HR'){ ?>
                              <?php foreach ($EmployeeTodayAttn as $key => $PresentToday): ?>
                                <tbody>
                                    <tr>
                                      <td><?= $PresentToday['FullName']; ?></td>
                                      <td><?= $PresentToday['Attendance']; ?></td>
                                      <td><?= $PresentToday['Status']; ?></td>
                                    </tr>
                                </tbody>
                              <?php endforeach ?>
                            <?php }elseif($rol == 'supervisor' || $rol== 'HR'){
                             foreach ($EmployeeTodayAttnSup as $key => $AttnSup):?>
                                <tbody>
                                    <tr>
                                      <td><?= $AttnSup['FullName']; ?></td>
                                      <td><?= $AttnSup['Attendance']; ?></td>
                                      <td><?= $AttnSup['Status']; ?></td>
                                    </tr>
                                </tbody>
                                <?php endforeach;
                            }else{
                              //nothing
                            }?>
                            </table>
                          </div>
                        </div>
 <?php if  ($rol== 'admin' || $rol== 'HR'):?>
        <div class="col-lg-12 well">
         supervisor Leave Left to Verify
        </div>

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
                              <?php
//                              echo '<label class="control-label">Employee</label>';
//                              echo Select2::widget([
//                             'name' => 'user',
//                             'value' => '',
//                             'data' => $Employee,
//                             'options' => ['multiple' => true, 'placeholder' => 'Select Employee ......']
//                         ]);
                                ?>
                          </div>
                            <button type="button" class="btn btn-success btn-lg btn-block post">Post</button>
                        <?php ActiveForm::end(); ?>    
                   </div>
             </div>
            <?php endif;?>
                        <div class="col-lg-12 well" >
                                <?php foreach($PostStatus as $status):  ?> 
                               <div class="panel panel-default single-post">
                                 <div class="panel-heading"><?=strtoupper($status->Type)?></div>  
                                 <div class="panel-body">
                              <h5><strong><?php $user=Employee::find(['UserID','FullName'])->where(['UserID'=>$status->InsertedBy])->one();
                              echo $user->FullName?></strong><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                              <?=$status->Title;?><br>
                              <span style="font-size: 11px;"><?php
                                  echo ($status->InsertedDate);?></span>
                              </h5>
                               <p><?=$status->Description?></p>
                                 </div>
                              </div>
                          <?php endforeach; ?>
                        </div>
                   </div> 
         </div>     
</div>


<?php
$script=<<<JS
       $('div#dashboard').find('ul.status-post li').click(function(){
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
   });
      $('button.post').click(function() {
       var title=$('input#title').val();
       var description=tinyMCE.get('poststatus-description').getContent();
        //var employee=$('#w2').val();
        var type = $('div#dashboard').find('ul.status-post li.active').attr("data-type");
        
       loadpost(title,description,type);  
        });
     
        function loadpost(title,description,type){
            $.ajax({
           url: 'default/savepost',
           type: 'post',
           data: {title:title,description:description,type:type},
           success: function (data) {
                      }
          });
        
        }
loadLoginTime();
  function loadLoginTime(){
      var e =   $('div#dashboard').find('div#checkInTime span.checkin-time');
       e.parents('div#checkInTime').addClass('hide');
    if(e.text().trim().length == 0){
      $.ajax({
           url: 'default/todaylog',
           type: 'post',
           success: function(data){
                if(data.length > 0){
                  e.parents('div#checkInTime').removeClass('hide'); 
                  e.text(data);
                }
           }
          });

      }
  }
    
JS;
$this->registerJs($script);
?> 

<?php 
  $this->registerCSS("

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
    ");
 ?>     

            
          