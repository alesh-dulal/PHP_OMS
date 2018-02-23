<?php 
 use yii\helpers\Html;
$this->title = Yii::$app->session['FullName'];
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
                  <img src=<?php echo $URL."/uploads/profile/".$Result['Image']?> height="350" width="250" alt="Image" class="img-rounded img-responsive" />
               </div>
               <div class="col-sm-6 col-md-8">
                  <h4>
                     <?= $Result['FullName']; ?>
                  </h4>
                  <p><cite title="<?= $Result['PermanantAddress']; ?>"><?= $Result['PermanantAddress']; ?><i class="glyphicon glyphicon-map-marker">
                  </i></cite></p>
                  <p>
                     <i class="glyphicon glyphicon-envelope"></i><?= $Result['Email']; ?>
                     <br />
                     <i class="glyphicon glyphicon-gift"></i><?= $Result['DOB']; ?>

<br />
                     <i class="glyphicon glyphicon-user"></i><?= $Result['SupervisorName']; ?>
                     <br />
                     <i class="glyphicon glyphicon-user"></i><?= $Result['Department']; ?>
                     <br />
                     <i class="glyphicon glyphicon-briefcase"></i><?= $Result['Designation']; ?>
                  </p>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <br>
                  <div class="col-lg-12">
                     <div class="col-lg-3"><strong>HireDate:</strong> <?= $Result['HireDate']; ?></div>
                     <div class="col-lg-3"><strong>Join Date:</strong> <?= $Result['JoinDate']; ?></div><div class="col-lg-3"><strong>Login Time:</strong> <?= $Result['LoginTime']; ?></div>
                     <div class="col-lg-3"><strong>Logout Time:</strong> <?= $Result['LogoutTime']; ?></div>
                  </div>
               </div> 
                  <hr>

               <div class="row">
                  <div class="col-lg-12">
                     <div class="col-lg-4">
                        <strong>CitizenNumber:</strong> 
                     <?= $Result['CitizenNumber']; ?>
                     </div>
                     <div class="col-lg-4">
                        <strong>CITNumber:</strong> 
                     <?= $Result['CITNumber']; ?>
                     </div>
                     <div class="col-lg-4">
                        <strong>PANNumber:</strong> 
                     <?= $Result['PANNumber']; ?>
                     </div>
                  </div>
               </div>
                             
            <hr>
               <div class="row">
                  <div class="text-center"><strong>Emergency Contacts</strong></div>
                  <br>
                  <div class="col-lg-12">
                     <div class="col-lg-4"><strong>Name:</strong> <?= $Result['EmergencyContact1Name']; ?></div>
                     <div class="col-lg-4"><strong>Relation:</strong> <?= $Result['EmergencyContact1Relation']; ?></div>
                     <div class="col-lg-4"><strong>Cell:</strong> <?= $Result['EmergencyContact1Cell']; ?></div>
                  </div>
                  <div class="col-lg-12">
                     <div class="col-lg-4"><strong>Name:</strong> <?= $Result['EmergencyContact2Name']; ?></div>
                     <div class="col-lg-4"><strong>Relation:</strong> <?= $Result['EmergencyContact2Relation']; ?></div>
                     <div class="col-lg-4"><strong>Cell:</strong> <?= $Result['EmergencyContact2Cell']; ?></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
 </div>
<?php 
	$this->registerCSS("

	.glyphicon { 
		 margin-bottom: 10px;
		 margin-right: 10px;
	}
		");
 ?>