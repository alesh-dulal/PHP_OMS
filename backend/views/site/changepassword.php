<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
?>


<div class="site-changepassword">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
    	<?php echo isset($redirect)?$redirect:""; ?>
        <div class="col-lg-5">
            <form method="POST" action="/oms/site/changepassword">
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter Old Password"  name="oldpassword" autofocus="true"/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter New Password"  name="newpassword"/>
                </div>               
                 <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm New Password"  name="confirmpassword"/>
                </div>
                <div> <span class="label label-danger"><?php echo isset($Error)?$Error:""; ?></span> <span class="label label-default"><?php echo isset($Success)?$Success:""; ?></span> </div>
                <button type="submit" class="btn btn-primary" name="change">Change</button>
            </form>
        </div>
    </div>
</div>