<?php
   use yii\helpers\Html;
   use yii\grid\GridView;
   use yii\widgets\ActiveForm;

   use kartik\select2\Select2;
   use yii\helpers\ArrayHelper;
   use yii\bootstrap\Modal;

   
   /* @var $this yii\web\View */
   /* @var $searchModel backend\modules\user\models\EmployeeSearch */
   /* @var $dataProvider yii\data\ActiveDataProvider */
   
   $this->title = 'Settings';
   $this->params['breadcrumbs'][] = $this->title;

   $html = "";
foreach($LeaveType as $LT){
    $html .= "<option value=".$LT['ListItemID'].">".$LT['Title']."</option>";
}
   ?>

   <!-- Role Settings -->
   <?php if (Yii::$app->session['Role']== 'Admin' || Yii::$app->session['Role']== 'Supervisor') { ?>
    <?= Html::a('Role Setting ',['/user/role/index'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Members ',['/user/members/index'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Holiday ',['/holiday/holiday'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Report ',['/report/report/view'], ['class' => 'btn btn-primary']) ?>
 <?php } ?>

 <?php if (Yii::$app->session['Role']== 'admin' || Yii::$app->session['Role']== 'Supervisor') { ?>
    <?= Html::a('Email Settings',['/mail/emailtemplate/index'], ['class' => 'btn btn-primary']) ?>
 <?php } ?>
 <?php if (Yii::$app->session['Role']== 'admin' || Yii::$app->session['Role']== 'Supervisor') { ?>
   <?= Html::a('Payroll Setting', ['salaryamendment'], ['class'=>'comm btn btn-info']) ?>
   <?= Html::a('Leave Accrue ',['leaveaccrue'], ['class' => 'btn btn-primary']) ?>
   <?= Html::a('Leave Initilize ',['leaveinit'], ['class' => 'btn btn-default']) ?>
 <?php } ?>


<div class="Settings">
   <div class="container">
      <h1>
         <?= Html::encode($this->title) ?>
      </h1>
      <ul id="tabList" class="nav nav-tabs">
         <li class="active">
            <span data-toggle="tab" class="item-tab hand" data-id="containerDepartment">Department
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerDesignation">Designation
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerLeavetype">Leave Type</span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerRoom">Room</span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerShift">Shift
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerStockUnit">Stock Unit
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerStockCategory">Stock Category
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerPayroll">Payroll Tracker
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerAccrue">Leave Accrue Tracker
            </span>
         </li>
      </ul>

      <div id="tabContainer" class="tab-content">
         <div id="containerDepartment" class="tab-pane fade in active" data-active="department">

            <h3>Department</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>
                  <?= $form->field($model, 'Title')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title']) ?>

                  <?= Html::button('Save', ['class' => 'btn btn-primary department-save', 'value'=>'save','data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>
              <div class="col-lg-8 data-department">
                <table class="table table-bordered"><thead><th>S.N</th><th>Name</th><th>Action</th></thead><tbody></tbody></table>
              </div>
            </div> 
         </div>

         <div id="containerDesignation" class="tab-pane fade" data-active="designation">
            <h3>Designation</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>
                  <?= $form->field($model, 'Title')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title']) ?>

                  

                  <?= Html::button('Save', ['class' => 'btn btn-primary designation-save', 'value'=>'save','data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>
              <div class="col-lg-8 data-designation">
                 <table class=" table table-bordered"><thead><th>S.N</th><th>Name</th><th>Action</th></thead><tbody></tbody></table>
              </div>
            </div>
         </div>

         <div id="containerLeavetype" class="tab-pane fade" data-active="leavetype">
            <h3>LeaveType</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin([
                    'action' => '#',
                    'options' => [
                              'method' => '#',
                            ]
                    ]); ?>
                  <?= $form->field($model, 'Title')->textInput(['maxlength' => true, 'style'=>'width:300px'])->label('Name') ?>
                  <?= $form->field($model, 'Value')->textInput([
                                 'type' => 'number',
                            'maxlength' => true, 'style'=>'width:300px'])->label('Total Number of Leave Days') ?>


                          <?=$form->field($model, 'Options')->radioList([
                                'male' => 'Male', 
                                'female' => 'Female',
                                'all' => 'All'
                                ]); ?>


                  <?= Html::button('Save', ['class' => 'btn btn-primary leavetype-save', 'value'=>'save' ,'data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>

              <div class="col-lg-6 data-leavetype">
                <table class="table table-bordered"><thead><th>S.N</th><th>Name</th><th>Days</th><th>Options</th><th>Action</th></thead><tbody></tbody></table>
              </div>
             
            </div>
         </div>

         <div id="containerRoom" class="tab-pane fade" data-active="room">
            <h3>Room</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>

                  <?= $form->field($model, 'Title')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title']) ?>
                  <?= $form->field($model, 'Value')->textInput(['type'=>'number', 'maxlength' => true, 'style'=>'width:300px', 'id'=>'value'])->label('Occupancy') ?>

                  <?= Html::button('Save', ['class' => 'btn btn-primary room-save', 'value'=>'save' ,'data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>
              <div class="col-lg-8 data-room">
                <table class="table table-bordered"><thead><th>S.N</th><th>Name</th><th>Occupancy</th><th>Action</th></thead><tbody></tbody></table>
              </div>
            </div>
         </div>

         <div id="containerShift" class="tab-pane fade" data-active="shift">
            <h3>Shift</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>
                  <?= $form->field($model, 'Title')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title']) ?>

                  <?= $form->field($model, 'Value')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title'])->label('In Time') ?>

                  <?= $form->field($model, 'Options')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title'])->label('Out Time') ?>

                  <?= Html::button('Save', ['class' => 'btn btn-primary shift-save', 'value'=>'save' ,'data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>

              <div class="col-lg-6 data-shift">
                 <table class="table table-bordered"><thead><th>S.N</th><th>Name</th><th>In Time</th><th>Out Time</th><th>Action</th></thead><tbody></tbody></table>
              </div>
            </div>
         </div>  



         <div id="containerStockUnit" class="tab-pane fade" data-active="stockunit">
            <h3>Stock Unit</h3>
              <div class="col-lg-12">

              <?php $form = ActiveForm::begin(); ?>
              <div class="col-lg-4">
              <div class="well">

              <?= $form->field($model, 'Title')->textInput(['maxlength' => true]) ?>

              <?= $form->field($model, 'ParentID')->widget(Select2::classname(), [
                   'data' => $StockUnitParent,
                   'language' => 'en',
                   'options' => ['placeholder' => 'Select a Item ...','id'=>'Item'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ])->label("Parent");?>

              <?= $form->field($model, 'Value')->textInput(['maxlength' => true]) ?>

              <div class="form-group">
              <?= Html::button('Save', ['class' => 'btn btn-primary stockunit-save', 'value'=>'save' ,'data-id'=>'0']) ?>
              </div>
              </div>
              </div>
              <?php ActiveForm::end(); ?>

              <div class="col-lg-6 data-stockunit">
                <table class="table table-bordered"><thead><th>S.N</th><th>Name</th><th>Parent</th><th>Value</th><th>Action</th></thead><tbody></tbody></table>
              </div>
              </div>
         </div>         

         <div id="containerStockCategory" class="tab-pane fade" data-active="stockcategory">
            <h3>Stock Category</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>
                  <?= $form->field($model, 'Title')->textInput(['maxlength' => true, 'style'=>'width:300px', 'id'=>'title']) ?>

                  <?= Html::button('Save', ['class' => 'btn btn-primary stockcategory-save', 'value'=>'save','data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>
              <div class="col-lg-8 data-stockcategory">
                <table class="table table-bordered "><thead><th>S.N</th><th>Name</th><th>Action</th></thead><tbody></tbody></table>
              </div>
            </div> 
         </div>         

         <div id="containerPayroll" class="tab-pane fade" data-active="payroll">
            <h3>Payroll Tracker</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>
                  <label for="Year">Year
        </label>
        <?php echo years(); ?>

                  <label for="month">Month
        </label>
        <?php echo months(); ?>

                  <?= Html::button('Save', ['class' => 'btn btn-primary payroll-save', 'value'=>'save','data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>
              <div class="col-lg-8 data-payroll">
                <table class="table table-bordered "><thead><th>S.N</th><th>Year</th><th>Month</th></thead><tbody></tbody></table>
              </div>
            </div> 
         </div>

         <div id="containerAccrue" class="tab-pane fade" data-active="accrue">
            <h3>Leave Accrue Tracker</h3>
            <div class="col-lg-12">
              <div class="col-lg-4">
                
               <div class="well">
                  <?php $form = ActiveForm::begin(); ?>
                  <div class="form-group">
            <label for="leaveType">Leave Type</label>
            <select class="employeeID form-control select-leave-type"  name="leavetype" id="leaveType" >
               <option value="0" disabled="true" selected="true">--Select Leave Type--</option>
               <?= $html ?>
            </select>
         </div>
                  <label for="Year">Year
        </label>
        <?php echo years(); ?>

                  <label for="month">Month
        </label>
        <?php echo months(); ?>

                  <?= Html::button('Save', ['class' => 'btn btn-primary leave-accrue-save', 'value'=>'save','data-id'=>'0']) ?>
               </div>
               <?php ActiveForm::end(); ?>
            
              </div>
              <div class="col-lg-8 data-leave-accrue">
                <table class="table table-bordered "><thead><th>S.N</th><th>Year</th><th>Month</th><th>Leave Type</th></thead><tbody></tbody></table>
              </div>
            </div> 
         </div>


      </div>
   </div>
</div>

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

     ");
    ?>


<?php 
$js = <<< JS
$(document).ready(function() {
  
  $("input[value='all']").attr('checked', true);

    $(".nav-tabs span").click(function() {
        $(this).tab("show");
    });

    $("ul#tabList").find("li span").click(function() {
        var ele = $("div#tabContainer");
        ele.find("div.tab-pane").removeClass("in active");
        var current = $(this).attr("data-id");
        ele.find("div#" + current).addClass("in active");
        $('div#tabContainer').find('button').attr('data-id',0);
        $('div#tabContainer').find('input[type=text]').val('');
    });

    $("div#containerDepartment").find('button.department-save').click(function() {

        var identity = $('.department-save').attr('data-id');
        var title =  $("div#containerDepartment").find('input[name="Listitems[Title]"]').val();
        SaveRecord("department", title, identity, "value", "options", function(res){
          if(res.result != undefined && res.result === true){
            GetDepartment();
          }else{

          }
        });
    }); 

    $("div#containerDesignation").find('button.designation-save').click(function() {
        var identity = $('.designation-save').attr('data-id');
        var title =  $("div#containerDesignation").find('input[name="Listitems[Title]"]').val();
        SaveRecord("designation", title, identity, "value", "options", function(res){
        
          if(res.result != undefined && res.result=== true){
            GetDesignation();
          }else{

          }
        });
    });

    $("div#containerLeavetype").find('button.leavetype-save').click(function() {
        var identity = $('.leavetype-save').attr('data-id');
        var title =  $("div#containerLeavetype").find('input[name="Listitems[Title]"]').val();
        var value = $("div#containerLeavetype").find('input[name="Listitems[Value]"]').val();
        var options = $("div#containerLeavetype").find('input[name="Listitems[Options]"]:checked').val();
        SaveRecord("leavetype", title, identity, value, options, function(res){
        
          if(res.result != undefined && res.result=== true){
            GetLeavetype();
          }else{

          }
        });
    });

    $("div#containerRoom").find('button.room-save').click(function() {

        var identity = $('.room-save').attr('data-id');

        var value = $("div#containerRoom").find('input[name="Listitems[Value]"]').val();
        var title =  $("div#containerRoom").find('input[name="Listitems[Title]"]').val();
        SaveRecord("room", title, identity, value, "options", function(res){
        
          if(res.result != undefined && res.result === true){
            GetRoom();
          }else{

          }
        });
    }); 

    $("div#containerShift").find('button.shift-save').click(function() {

        var identity = $('.shift-save').attr('data-id');
        var value = $("div#containerShift").find('input[name="Listitems[Options]"]').val();
        var options = $("div#containerShift").find('input[name="Listitems[Value]"]').val();
        var title =  $("div#containerShift").find('input[name="Listitems[Title]"]').val();

        SaveRecord("shift", title, identity, value, options, function(res){
          if(res.result != undefined && res.result === true){
            GetShift();
          }else{

          }
        });
    }); 

    $("div#containerStockUnit").find('button.stockunit-save').click(function() {
        var identity = $('.stockunit-save').attr('data-id');

        var value = $("div#containerStockUnit").find('input[name="Listitems[Value]"]').val();
        var options = $("div#containerStockUnit").find('select[name="Listitems[ParentID]"]').val();
        var title =  $("div#containerStockUnit").find('input[name="Listitems[Title]"]').val();

        SaveRecord("stockunit", title, identity, value, options==""?0:options, function(res){

          if(res.result != undefined && res.result === true){
            GetStockunit();
          }else{

          }
        });
    });

    $("div#containerStockCategory").find('button.stockcategory-save').click(function() 
    {
        var identity = $('.stockcategory-save').attr('data-id');
        var title =  $("div#containerStockCategory").find('input[name="Listitems[Title]"]').val();
        SaveRecord("stockcategory", title, identity, "value", "options", function(res){
          if(res.result != undefined && res.result === true){
            GetStockCategory();
          }else{

          }
        });
    });  

    $("div#containerPayroll").find('button.payroll-save').click(function() 
    {
        var identity = $('.stockcategory-save').attr('data-id');
        var title =  $("div#containerPayroll").find('select[name="Listitems[Title]"]').val();
        var value =  $("div#containerPayroll").find('select[name="Listitems[Value]"]').val();
        SaveRecord("payroll", title, identity, value, "options", function(res){
          if(res.result != undefined && res.result === true){
            GetPayroll();
          }else{

          }
        });
    });

    $("div#containerAccrue").find('button.leave-accrue-save').click(function() 
    {
        var identity = $('.stockcategory-save').attr('data-id');
        var title =  $("div#containerAccrue").find('select[name="Listitems[Title]"]').val();
        var value =  $("div#containerAccrue").find('select[name="Listitems[Value]"]').val();
        var options =  $("div#containerAccrue").find('select[name="leavetype"] option:selected').val();
        SaveRecord("accrue", title, identity, value, options, function(res){
          if(res.result != undefined && res.result === true){
            GetAccrue();
          }else{

          }
        });
    });
  
    GetDepartment();
    GetDesignation();
    GetLeavetype();
    GetRoom();
    GetShift();
    GetStockunit();
    GetStockCategory();
    GetPayroll();
    GetAccrue();

    function GetDepartment()
    {
      GetRecord('department',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
  
          }
          $('div#containerDepartment').find('table tbody').html(tr);
      })
    }

    function GetDesignation()
    {
      GetRecord('designation',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
          }
          $('div#containerDesignation').find('table tbody').html(tr);
      })
    }

    function GetLeavetype()
    {
      GetRecord('leavetype',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            tr+='<td>'+dat[i].Value+'</td>';
            tr+='<td>'+dat[i].Options+'</td>';
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
          }

          $('div#containerLeavetype').find('table tbody').html(tr);
      });
    }

    function GetRoom()
    {
      GetRecord('room',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            tr+='<td>'+dat[i].Value+'</td>';
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
          }

          $('div#containerRoom').find('table tbody').html(tr);
      })
    }

    function GetShift()
    {
      GetRecord('shift',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            tr+='<td>'+dat[i].Options+'</td>';
            tr+='<td>'+dat[i].Value+'</td>';
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
          }

          $('div#containerShift').find('table tbody').html(tr);
      })
    }

    function GetStockunit()
    {
      GetRecord('stockunit',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            tr+='<td data-parentID="'+dat[i].ParentID+'">'+dat[i].Parent+'</td>';
            tr+='<td>'+dat[i].Value+'</td>';
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
          }

          $('div#containerStockUnit').find('table tbody').html(tr);
      })
    }

    function GetStockCategory()
    {
      GetRecord('stockcategory',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Title+'</td>';
            tr+='<td><span class="hand edit" data-id="'+dat[i].ListItemID+'">edit</span></td>';
          }
          $('div#containerStockCategory').find('table tbody').html(tr);
      })
    }    

    function GetPayroll()
    {
      GetPayrollRecord('payroll',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Year+'</td>';
            tr+='<td>'+dat[i].Month+'</td>';
          }
          $('div#containerPayroll').find('table tbody').html(tr);
      })
    }

    function GetAccrue()
    {
      GetAccrueRecord('accrue',function(dat){
        tr='';
          for(i=0;i<dat.length;i++)
          {
            tr+='<tr><td>'+(i+1)+'</td>';
            tr+='<td>'+dat[i].Year+'</td>';
            tr+='<td>'+dat[i].Month+'</td>';
            tr+='<td>'+dat[i].LeaveType+'</td>';
          }
          $('div#containerAccrue').find('table tbody').html(tr);
      })
    }



    function SaveRecord(type, title, identity, value, options,callMe,parentID) {
        $.ajax({
            type: "POST",
            url: "settings/savedata",
            data: {
                "type": type,
                "title": title,
                "identity": identity,
                "value": value,
                "options": options
            },

            dataType:'json',
            cache: false,
            success: function(data) {
              showMessage("Added Successfully.");
              callMe(data);
              $('body').find('button').attr('data-id',0);
              ClearField(type);
            },

            error:function(){
                showError("Addition Failed. Server Error.");
            }
        });
    }

  function GetRecord(type,callMe)
  {
    $.ajax({
          type: "POST",
          url: "settings/retrivedata",
          data:{
            type:type,
          },
          dataType:'json',
          cache: false,
          success: function(data) {
            callMe(data);
          },
          error:function(){
           showError("Record Fetch Failed. Server Error.");
          }
        });
  } 

  function GetAccrueRecord(type, callMe)
  {
    $.ajax({
          type: "POST",
          url: "settings/retriveaccrue",
          data:{
            type:type,
          },
          dataType:'json',
          cache: false,
          success: function(data) {
            callMe(data);
          },
          error:function(){
           showError("Server Error.");
          }
        });
  }        

  function GetPayrollRecord(type, callMe)
  {
    $.ajax({
          type: "POST",
          url: "settings/retrivepayrolltrack",
          data:{
            type:type,
          },
          dataType:'json',
          cache: false,
          success: function(data) {
            callMe(data);
          },
          error:function(){
           showError("Server Error.");
          }
        });
  }        
});

$('div#tabContainer').on('click','span.edit' ,function(){
 var type =  $("div#tabContainer").find("div.active").attr("data-active");
  GetSingleRecord(90,type,$(this));
});

function GetSingleRecord(identity,type,edit)
{
  switch(type)
  {
    case "department":
    var ele=$('div#containerDepartment');
    var vary = ele.find("edit").attr("data-id");
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    break;

    case "designation":
    var ele=$('div#containerDesignation');
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    break;

    case "leavetype":
    var ele=$('div#containerLeavetype');
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    ele.find('input[name="Listitems[Value]"]').val(edit.parents('tr').find('td:eq(2)').text());
    var selectedGender=edit.parents('tr').find('td:eq(3)').text();
    ele.find('input[value="'+selectedGender+'"]').prop('checked', true);
    break;

    case "room":
    var ele=$('div#containerRoom');
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    ele.find('input[name="Listitems[Value]"]').val(edit.parents('tr').find('td:eq(2)').text());
    break;

    case "shift":
    var ele=$('div#containerShift');
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    ele.find('input[name="Listitems[Value]"]').val(edit.parents('tr').find('td:eq(2)').text());
    ele.find('input[name="Listitems[Options]"]').val(edit.parents('tr').find('td:eq(3)').text());
    break;

    case "stockunit":
    var ele=$('div#containerStockUnit');
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    ele.find('select[name="Listitems[ParentID]"] ').val(edit.parents('tr').find('td:eq(2)').attr('data-parentID')).trigger('change');
    ele.find('input[name="Listitems[Value]"]').val(edit.parents('tr').find('td:eq(3)').text());
    break;

    case "stockcategory":
    var ele=$('div#containerStockCategory');
    ele.find('button').attr('data-id',edit.attr('data-id'));
    ele.find('input[name="Listitems[Title]"]').val(edit.parents('tr').find('td:eq(1)').text());
    break;
  }
}

function ClearField(type){
  var type = CapitalizeFirstLetter(type);
  var ele = 'div#container'+type;
  $(ele).find('input').val('');
  $(ele).find('input:radio').prop('checked', false);
  $(ele).find("input:radio[value='all']").attr('checked', true);;

  $(ele).find('select').val('0');
}

function CapitalizeFirstLetter(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

JS;

$this->registerJS($js);

?>
<?php

function months($selctedMonth = 'january')
{
  $months = '<div class="select"><select name="Listitems[Value]" class="form-select custom-control month" id="month" size="1">';
  $months.= '<option value="0" disabled>--Select Month--</option>';
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
  $years = '<div class="select"><select class="form-select custom-control id="Year" name="Listitems[Title]" size="1">';
  $years.= '<option value="0" disabled>--Select Year--</option>';
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
  
$this->registerCSS("

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