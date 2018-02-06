<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\ListitemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="listitems-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ListItemID') ?>

    <?= $form->field($model, 'Type') ?>

    <?= $form->field($model, 'Title') ?>

    <?= $form->field($model, 'Value') ?>

    <?= $form->field($model, 'IsParent') ?>

    <?php // echo $form->field($model, 'Options') ?>

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
