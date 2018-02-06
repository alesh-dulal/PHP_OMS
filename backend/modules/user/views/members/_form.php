<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Members */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'FullName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Address')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model, 'CellPhone')->widget(PhoneInput::className(), [
    'jsOptions' => [
        'preferredCountries' => ['np', 'in', 'ua'],
    ]
    ]);?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'Type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Remarks')->textarea(['rows' => 6]) ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
