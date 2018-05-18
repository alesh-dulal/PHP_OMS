<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   
   use kartik\select2\Select2;
   use kartik\date\DatePicker;
   use yii\widgets\Pjax;
   
   use backend\modules\user\models\Role;
   use backend\modules\user\models\Employee;
   use backend\modules\leave\models\Employeeleave;
   
   ?>
<?php 
   $this->title = "Approve Leave";
    ?>
<div class="container">
   <h1>
      <?= Html::encode($this->title) ?>
   </h1>
   <?php Pjax::begin(['id' => 'employee', 'clientOptions' => ['method' => 'POST']]) ?>
   <ul id="tabList" class="nav nav-tabs">
      <li class="active">
         <span class="item-tab hand" data-id="containerPending">
         	Pending
         </span>
      </li>
      <li>
         <span class="item-tab hand" data-id="containerApproved">
         	Approved
         </span>
      </li>
      <li>
         <span class="item-tab hand" data-id="containerRejected">
         	Rejected
         </span>
      </li>
   </ul>
   <div id="tabContainer" class="tab-content">
      <div id="containerPending" class="tab-pane fade in active" data-active="pending">
         <h3>Pending</h3>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Employee  Name</th>
                  <th>Leavetype</th>
                  <th>DateRange</th>
                  <th>No Of Days</th>
                  <th>Reason</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>

               <?php foreach ($ApproveLeave as $key => $Leave):?>
               <?php if ($Leave['IsApproved']==0 && $Leave['IsRejected']==0): ?>
               <tr>
                  <td><?= $Leave['FullName'] ?></td>
                  <td><?= $Leave['LeaveType'] ?></td>
                  <td><?= $Leave['DateRange'] ?></td>
                  <td><?= $Leave['NoOfDays'] ?></td>
                  <td><?= $Leave['Reason'] ?></td>
                  <td>
                     <span class="glyphicon glyphicon-ok hand approve" data-id=<?= $Leave['EmployeeLeaveID'] ?> leave-id=<?= $Leave['LeaveID'] ?> employee-id=<?= $Leave['EmployeeID'] ?> days=<?= $Leave['NoOfDays'] ?>></span>
                     <span class="glyphicon glyphicon-remove hand reject" data-id=<?= $Leave['EmployeeLeaveID'] ?> employee-id=<?= $Leave['EmployeeID'] ?> days=<?= $Leave['NoOfDays'] ?>></span>
                  </td>
               </tr>
               <?php endif ?>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
      <div id="containerApproved" class="tab-pane fade" data-active="approved">
         <h3>Approved</h3>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Employee Name</th>
                  <th>Leavetype</th>
                  <th>Reason</th>
                  <th>DateRange</th>
                  <th>No Of Days</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($ApproveLeave as $key => $Leave){ ?>
               <?php if ($Leave['IsApproved']==1 && $Leave['IsRejected']==0){  ?>		
               <tr>
                  <td><?= $Leave['FullName'] ?></td>
                  <td><?= $Leave['LeaveType'] ?></td>
                  <td><?= $Leave['DateRange'] ?></td>
                  <td><?= $Leave['NoOfDays'] ?></td>
                  <td><?= $Leave['Reason'] ?></td>
               </tr>
               <?php } ?>
               <?php } ?>
            </tbody>
         </table>
      </div>
      <div id="containerRejected" class="tab-pane fade" data-active="rejected">
         <h3>Rejected</h3>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Employee Name</th>
                  <th>Leavetype</th>
                  <th>Leave Reason</th>
                  <th>DateRange</th>
                  <th>No Of Days</th>
                  <th>Reject Reason</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($ApproveLeave as $key => $Leave){ ?>
               <?php if ($Leave['IsApproved']==0 && $Leave['IsRejected']==1){  ?>		
               <tr>
                  <td><?= $Leave['FullName'] ?></td>
                  <td><?= $Leave['LeaveType'] ?></td>
                  <td><?= $Leave['DateRange'] ?></td>
                  <td><?= $Leave['NoOfDays'] ?></td>
                  <td><?= $Leave['Reason'] ?></td>
                  <td><?= $Leave['RejectedNote'] ?></td>
               </tr>
               <?php } ?>
               <?php } ?>
            </tbody>
         </table>
      </div>
      <?php Pjax::end(); ?>
   </div>
<!-- start of modal -->
<div id="leaveRejectModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reject Note</h4>
      </div>
      <div class="modal-body">
        <span class = "rejectNote"></span>
        <textarea class="note"></textarea>
        <label for=""></label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger save-note" data-id="0">Done</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end of modal -->
</div>


<?php 
$js = <<< JS

$(document).ready(function(){
   	$(".nav-tabs span").click(function() {
           $(this).tab("show");
       });
       $("ul#tabList").find("li span").click(function() {
           var ele = $("div#tabContainer");
           ele.find("div.tab-pane").removeClass("in active");
           var current = $(this).attr("data-id");
           ele.find("div#" + current).addClass("in active");
       });
   });
  
 $("div#containerPending").find('span.approve').click(function() {
  var Identity = $('div#containerPending').find('span.approve').attr('data-id');
  var EmployeeID = $('div#containerPending').find('span.approve').attr('employee-id');
  var LeaveID = $('div#containerPending').find('span.approve').attr('leave-id');
  var leaveDays = $('div#containerPending').find('span.approve').attr('days');
  console.log(EmployeeID);
  console.log(leaveDays);
	LeaveUpdate(true, 0, Identity, EmployeeID, leaveDays, LeaveID, function(){
	});
 });

 $("div#containerPending").find('span.reject').click(function() {
	$('div#leaveRejectModal').modal();
 }); 

 $("div#leaveRejectModal").find('button.save-note').click(function() {
  var Note = $('div#leaveRejectModal').find('textarea.note').val();
	var Identity = $('div#containerPending').find('span').attr('data-id');
  var EmployeeID = $('div#containerPending').find('span').attr('employee-id');
  var LeaveID = $('div#containerPending').find('span').attr('leave-id');
  var leaveDays = $('div#containerPending').find('span').attr('days');

  if(Note.length < 7){
    $('div#leaveRejectModal').find('span.rejectNote').text("Reject Note Not Enough");
  }else{
      LeaveUpdate(false, Note , Identity, EmployeeID, leaveDays, LeaveID, function(){
      });

    $('div#leaveRejectModal').modal('hide');
  }	
 });

 function LeaveUpdate(status, remarks,  identity, employeeID, leaveDays, leaveID, callMe){
 	  $.ajax({
            type: "POST",
            url: "approveleave",
            data: {
                "status": status,
                "remarks": remarks,
                "identity": identity,
                "employeeID": employeeID,
                "leaveDays": leaveDays,
                "leaveID": leaveID,
            },

            dataType:'json',
            cache: false,
            success: function(data) {
             location.reload();
              showMessage("Updated Successfully.");
              // callMe(data);
          },
          
          error:function(){
            showError("Update Failed. Server Error.")
          }
        });
 }

JS;
$this->registerJS($js);
 ?>

 <?php $this->registerCSS("
 .note{
 	height: 4em;
    width: 40em;
    border-radius: 5px;
 }
 .rejectNote{
  color:red;
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

 "); ?>
