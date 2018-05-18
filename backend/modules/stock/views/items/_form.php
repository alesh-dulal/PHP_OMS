<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\modules\stock\models\Unit;

/* @var $this yii\web\View */
/* @var $model backend\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="items-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-lg-10">
        <div class="well">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
                    </div>
                <div class="col-lg-6" style="padding-top: 25px">
                    <?= $form->field($model, 'IsLongLasting')->checkbox(); ?>
            </div>
                </div>
             <div class="row">
                <div class="col-lg-6">
                       <?= $form->field($model, 'CategoryID')->widget(Select2::classname(), [
                        'data' => $Category,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select a Category ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                    </div>
                 
                <div class="col-lg-6">
                       <?= $form->field($model, 'UnitID')->widget(Select2::classname(), [
                        'data' => $Unit,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select a Unit ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                </div>
                </div>
                
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
            </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
