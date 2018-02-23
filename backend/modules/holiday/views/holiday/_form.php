<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\modules\attendance\models\Year;


/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\Holiday */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="holiday-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
    <?= $form->field($model, 'Day')->label('Day')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'yyyy-mm-dd',],
                     'pluginOptions' => [
                         'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                         'autoclose'=>true,
                  ]
                ]);  ?>  
            </div>
        <div class="col-lg-4">
       
          <?php 
                    echo $form->field($model, 'Year')->widget(Select2::classname(), [
                            'data' => $year,
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Year...'],
                            'pluginOptions' => [
                                    'allowClear' => true
                                 ],
                    ]);
                 ?>   
        </div>
   </div>
    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
     