<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
?>

<?php if (Yii::$app->session->hasFlash('ConfirmPasswordMismatching')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Error!!</h4>
    <?= Yii::$app->session->getFlash('ConfirmPasswordMismatching') ?>
  </div>
<?php endif; ?>
<div class="site-resetpassword">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Enter Your New Password</p>
    <div class="row">
        <div class="col-lg-5">
            <form method="POST" action="/oms/site/saveresetpassword">
                <div class="form-group">
                    <input name="UserID" type="hidden" value=<?= "'".$_GET['key']."'"; ?>>
                    <input type="password" class="form-control" placeholder="Enter New Password"  name="password1" autofocus="true"/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm New Password"  name="password2"/>
                </div>
                <button type="submit" class="btn btn-primary" name="change">Change</button>
            </form>
        </div>
    </div>
</div>
