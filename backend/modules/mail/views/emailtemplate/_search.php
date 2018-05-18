<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\mail\models\EmailtemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="emailtemplate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'EmailTemplateID') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Details') ?>

    <?= $form->field($model, 'CreatedBy') ?>

    <?= $form->field($model, 'CreatedDate') ?>

    <?php // echo $form->field($model, 'UpdatedBy') ?>

    <?php // echo $form->field($model, 'UpdatedDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
