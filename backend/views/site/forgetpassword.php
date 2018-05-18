<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forget Password';
?>

<?php if (Yii::$app->session->hasFlash('InvalidAttemptPasswordReset')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Error!!</h4>
    <?= Yii::$app->session->getFlash('InvalidAttemptPasswordReset') ?>
  </div>
<?php endif; ?>
<div class="site-forgetpassword">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the username to recreate password:</p>

    <div class="row">
        <div class="col-lg-5">
            <form method="POST" action="/oms/site/forgetpassword">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Enter Username Here...."  name="username" autofocus="true"/>
                </div>
                <button type="submit" class="btn btn-primary" name="reset">Reset</button>
            </form>
        </div>
    </div>
</div>
