<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\user\controllers\UserController;
use backend\modules\user\controllers\EmployeeController;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employee */

$this->title = $model->FullName;
$this->params['breadcrumbs'][] = ['label' => 'Virtual Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
   $HTTP = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'? "https://" : "http://";
   $URL = $HTTP . $_SERVER["SERVER_NAME"] . Yii::$app->urlManager->baseUrl
 ?>



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
                  <p><cite title="<?= $model['Address']; ?>"><?= $model['Address']; ?><i class="glyphicon glyphicon-map-marker">
                  </i></cite></p>
                  <p>
                     <i class="glyphicon glyphicon-envelope"></i><?= $model['Email']; ?>
                     <br />
                     <i class="glyphicon glyphicon-gift"></i><?= $model['DOB']; ?>

<br />
                     <i class="glyphicon glyphicon-user"></i><?= \backend\modules\user\models\Employee::findOne($model->SupervisorID)->FullName; ?>
                     <br />

                  </p>
                  </div>
               </div>
               <hr>

               <div class="row">
                  <div class="text-center"><strong>Emergency Contacts</strong></div>
                  <br>
                  <div class="col-lg-12">
                     <div class="col-lg-4"><strong>Name:</strong> <?= $model['EmergencyContactName']; ?></div>
                     <div class="col-lg-4"><strong>Relation:</strong> <?= $model['EmergencyContactRelation']; ?></div>
                     <div class="col-lg-4"><strong>Cell:</strong> <?= $model['EmergencyContactCellPhone']; ?></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
 </div>