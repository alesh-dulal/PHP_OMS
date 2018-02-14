<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Allowencesetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="allowencesetting-form">
    <div id="container">
        <ul id="tabList" class="nav nav-tabs">
         <li class="active">
            <span data-toggle="tab" class="item-tab hand" data-id="containerAllowence">Allowence
            </span>
         </li>
         <li>
            <span data-toggle="tab" class="item-tab hand" data-id="containerDeduction">Deduction
            </span>
         </li>
      </ul>
    </div>


    <div id="tabContainer" class="tab-content">
        <div id="containerAllowence" class="tab-pane fade in active" data-active="allowence">
                <h1>This is allowence</h1>
                <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'IsAllowence')->hiddenInput(['value'=>1])->label(false) ?>

    <?= $form->field($model, 'Title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Amount')->textInput() ?>

    <?= $form->field($model, 'Formula')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
        </div>
        <div id="containerDeduction" class="tab-pane fade in " data-active="deduction">
                <h1>This is Deduction</h1>
                <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'IsAllowence')->hiddenInput(['value'=>0])->label(false)?>

    <?= $form->field($model, 'Title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Amount')->textInput() ?>

    <?= $form->field($model, 'Formula')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php 
   $this->registerCss("
      .nav-tabs > li > span {
       margin-right: 2px;
       line-height: 1.42857143;
       border: 1px solid transparent;
       border-radius: 4px 4px 0 0;
   }
   
   .nav > li > span {
       position: relative;
       display: block;
       padding: 10px 15px;
   }

   .nav {
    list-style: none;
    }

  span {
     background-color: transparent;
      color: #337ab7;
      text-decoration: none;
  }
   
   span {
       color: #337ab7;
       text-decoration: none;
       background-color: transparent;
   }
   

   .nav-tabs > li.active > span, .nav-tabs > li.active > span:focus, .nav-tabs > li.active > span:hover {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: rgb(221, 221, 221);
    border-bottom-color: transparent;

     ");
    ?>

    

<?php 
$js = <<< JS
$(document).ready(function(){
    $(".nav-tabs span").click(function() {
        $(this).tab("show");
    });

    $("ul#tabList").find("li span").click(function() {
        var ele = $("div#tabContainer");
        ele.find("div.tab-pane").removeClass("in active");
        var current = $(this).attr("data-id");
        ele.find("div#" + current).addClass("in active");
    });
});
JS;

$this->registerJS($js);

?>