<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'UserId') ?>

    <?= $form->field($model, 'UserName') ?>

    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'Password') ?>

    <?= $form->field($model, 'IsPasswordReset') ?>

    <?php // echo $form->field($model, 'PasswordKey') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'CreatedDate') ?>

    <?php // echo $form->field($model, 'CreatedBy') ?>

    <?php // echo $form->field($model, 'UpdatedDate') ?>

    <?php // echo $form->field($model, 'UpdatedBy') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <?php // echo $form->field($model, 'IsDeleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
