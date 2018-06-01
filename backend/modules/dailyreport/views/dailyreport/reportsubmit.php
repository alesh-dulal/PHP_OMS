<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;
use backend\modules\user\models\Role;
use backend\modules\user\models\Employee;
use backend\modules\dailyreport\models\Dailyreport;

$date = $_GET['day'];
$this->title = "Daily Report of ".date('j-F',strtotime($date));
$jsonFlag = json_decode($flags, true);
?>

<div class="container">
  <h4 align="center">Daily Report of: <?= date('j F, Y',strtotime($date)) ?></h4>
  <div class="tab-content">
    <div id="dailyReport" class="tab-pane fade in active">
      <?php $form = ActiveForm::begin(); ?>
      <div class="well">
          <div class="row" style="padding-bottom:8px;">
            <div class="col-lg-1" >
            <?= $form->field($model, 'TotalTask')->textinput(['value'=>'1'])->label("Task") ?>
            <?= $form->field($model, 'Day')->hiddenInput(['value'=>$date])->label(false) ?>
            </div>
          </div>
          <div class="row report-body" id="reportBody" style="">
              <?= $form->field($model, 'Report')->textarea(['placeholder'=>'Write Your Report....','rows' => '3', 'required' => true]) ?>

            <?php if($jsonFlag['LoginFlag'] == 1){ ?>
            <div class="form-group">
              <?= $form->field($model, 'LoginLate')->textarea(['placeholder' => 'Write Your Reason for Login Late....','rows' => '3', 'required' => true]) ?>
            </div>
        <?php }
        if($jsonFlag['ExitFlag'] == 1){
         ?>
            <div class="form-group">
              <?= $form->field($model, 'ExitFast')->textarea(['placeholder' => 'Write Your Reason for Early Exit....','rows' => '3', 'required' => true]) ?>
            </div>
        <?php } ?>

          </div>
            <div class="form-group" style=" height:25px; padding-right:10px;padding-top:10px;">     
              <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
      </div>
  <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>

  <?php $this->registerCSS("
    .remarks{
      color:red;
    }

    textarea {
      resize: none;
    }
  ");?>