<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>
        
    <div class="col-lg-12">
        <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'MenuID')->checkboxList($Item,['separator'=>'<br/>']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>    
    <?php ActiveForm::end(); ?>

</div>
