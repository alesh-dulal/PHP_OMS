<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employeecommunication */
/* @var $form ActiveForm */
$this->title = 'Communication';
?>
<div class="communication">
<h2 align="center">Communication</h2>
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12">
            <?= $form->field($model, 'Details')->textarea()->label('Description'); ?>
            <div class="col-lg-3">
                <?= $form->field($model, 'Type')->radioList(array('direct'=>'Direct','indirect'=>'Indirect')); ?>
            </div>
            <div class="col-lg-9">
                <?= $form->field($model, 'Tags') -> label('Tags(Enter with Comma Separated)') ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- comm -->




        
    
        