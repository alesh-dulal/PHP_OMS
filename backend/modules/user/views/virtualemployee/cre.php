<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Virtualemployee */
/* @var $form ActiveForm */
?>
<div class="cre">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'PerArticle') ?>
        <?= $form->field($model, 'CreatedBy') ?>
        <?= $form->field($model, 'UpdatedBy') ?>
        <?= $form->field($model, 'IsActive') ?>
        <?= $form->field($model, 'IsDeleted') ?>
        <?= $form->field($model, 'CreatedDate') ?>
        <?= $form->field($model, 'UpdatedDate') ?>
        <?= $form->field($model, 'FullName') ?>
        <?= $form->field($model, 'Email') ?>
        <?= $form->field($model, 'Phone') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- cre -->
