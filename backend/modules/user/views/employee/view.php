<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\user\controllers\UserController;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employee */

$this->title = $model->FullName;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
   $HTTP = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'? "https://" : "http://";
   $URL = $HTTP . $_SERVER["SERVER_NAME"] . Yii::$app->urlManager->baseUrl
 ?>
<div class="employee-view">
    <div class="col-md-12 row"style="padding-right:0px;">
        <div class="col-md-8 title" align="right">
            <h1 align="right"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="actions col-md-4" align="right" style="padding-right:0px; padding-top: 20px;">
            <p>
        <?= Html::a('Update', ['update', 'id' => $model->EmployeeID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->EmployeeID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('Communication', ['communication', 'id' => $model->EmployeeID], ['class'=>'comm btn btn-info', 'attr-empid' => $model->EmployeeID]) ?>

    </p>
        </div>
    </div>

     <div id="employeeInfo">
    <div class="row">
      <div class="col-lg-12 col-sm-6 col-md-6">
         <div class="well">
            <div class="row">
               <div class="col-sm-6 col-md-4">
                  <img src=<?php echo $URL."/uploads/profile/".$model['Image']?> height="350" width="250" alt="Image" class="img-rounded img-responsive" />
               </div>
               <div class="col-sm-6 col-md-8">
                  <h4>
                     <?= $model['FullName']; ?>
                  </h4>
                  <p><cite title="<?= $model['PermanantAddress']; ?>"><?= $model['PermanantAddress']; ?><i class="glyphicon glyphicon-map-marker">
                  </i></cite></p>
                  <p>
                     <i class="glyphicon glyphicon-envelope"></i><a href=<?= "mailto:".$model['Email'].""?>><?= " ". $model['Email']; ?></a>
                     <br />
                     <i class="glyphicon glyphicon-gift"></i><?= " ".date('F d', strtotime($model['DOB'])); ?>

<br />
                     <i class="glyphicon glyphicon-user"></i><?= " ". \backend\modules\user\models\Employee::findOne($model['Supervisor'])->FullName; ?>
                     <br />
                     <i class="glyphicon glyphicon-user"></i><?= " ". \backend\modules\user\models\Listitems::findOne($model['DepartmentID'])->Title ?>
                     <br />
                     <i class="glyphicon glyphicon-briefcase"></i><?= " ". \backend\modules\user\models\Listitems::findOne($model['DesignationID'])->Title; ?>
                  </p>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <br>
                  <div class="col-lg-12">
                     <div class="col-lg-3"><strong>HireDate:</strong> <?= $model['HireDate']; ?></div>
                     <div class="col-lg-3"><strong>Join Date:</strong> <?= $model['JoinDate']; ?></div><div class="col-lg-3"><strong>Login Time:</strong> <?= $model['LoginTime']; ?></div>
                     <div class="col-lg-3"><strong>Logout Time:</strong> <?= $model['LogoutTime']; ?></div>
                  </div>
               </div> 
                  <hr>

               <div class="row">
                  <div class="col-lg-12">
                     <div class="col-lg-4">
                        <strong>CitizenNumber:</strong> 
                     <?= $model['CitizenNumber']; ?>
                     </div>
                     <div class="col-lg-4">
                        <strong>CITNumber:</strong> 
                     <?= $model['CITNumber']; ?>
                     </div>
                     <div class="col-lg-4">
                        <strong>PANNumber:</strong> 
                     <?= $model['PANNumber']; ?>
                     </div>
                  </div>
               </div>
                             
            <hr>
               <div class="row">
                  <div class="text-center"><strong>Emergency Contacts</strong></div>
                  <br>
                  <div class="col-lg-12">
                     <div class="col-lg-4"><strong>Name:</strong> <?= $model['EmergencyContact1Name']; ?></div>
                     <div class="col-lg-4"><strong>Relation:</strong> <?= $model['EmergencyContact1Relation']; ?></div>
                     <div class="col-lg-4"><strong>Cell:</strong> <?= $model['EmergencyContact1Cell']; ?></div>
                  </div>
                  <div class="col-lg-12">
                     <div class="col-lg-4"><strong>Name:</strong> <?= $model['EmergencyContact2Name']; ?></div>
                     <div class="col-lg-4"><strong>Relation:</strong> <?= $model['EmergencyContact2Relation']; ?></div>
                     <div class="col-lg-4"><strong>Cell:</strong> <?= $model['EmergencyContact2Cell']; ?></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
 </div>


</div>
<hr/>
<?php 

    $Role = UserController::CheckRole("employee");
    if ($Role == true)
        {
          echo '
                <div id="conDet" class="row">
                    <h4 align="center">Communication Minutes'.


                Html::a('See All', ['allcommunication', 'id' => $model->EmployeeID], ['class'=>'comm-all btn btn-primary btn-xs', 'attr-empid' => $model->EmployeeID])

                .'</h4>
                    <div class="communication-details">
                        <table class="table table-striped" id="conDetails">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Communicated With</th>
                                <th>Tags</th>
                            </tr>
                        </thead>
                        <tbody>
                        <!--=====Table Content Goes Here=====-->
                        </tbody>
                        </table>
                        
                    </div>
                </div>
';}
 ?>

<?php 
$js = <<< JS
$(document).ready(function(){
    var EmployeeID = $('a.comm').attr('attr-empid');
    GetDetails(EmployeeID);
});
function GetDetails(EmployeeID)
{
    $.ajax({
        type: "POST",
        url: "getcommunication",
        data: {
            "EmployeeID":EmployeeID,
        },
        dataType:'json',
        cache: false,
        success: function(data) {
         $('div#conDet').find('div.communication-details table#conDetails tbody').append(data['html']);  
        },
        error:function(data){
            showError('Communication Data is Missing');
        }
    });
}

JS;
$this->registerJS($js);
?>