<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\HolidaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="holiday-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'HolidayID') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Description') ?>

    <?= $form->field($model, 'Day') ?>

    <?= $form->field($model, 'Year') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <?php // echo $form->field($model, 'InsertedBy') ?>

    <?php // echo $form->field($model, 'InsertedDate') ?>

    <?php // echo $form->field($model, 'UpdatedBy') ?>

    <?php // echo $form->field($model, 'UpdatedDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
