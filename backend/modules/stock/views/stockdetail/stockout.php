<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;
use dosamigos\tinymce\TinyMce;
use backend\modules\stock\models\Items;
use backend\modules\user\models\User;

if($val=="damage"){
    $this->title = 'Damaged Items';
    $this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['/stock/stock/index']];
    $this->params['breadcrumbs'][] = $this->title;
}

/* @var $this yii\web\View */
/* @var $model backend\models\Stockdetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stockdetail-form">
<?php
    if($val=="damage")
    echo'<h2 style="padding-left: 350px">Damage Entry</h2>'
?>
    <?php
    if($val=="stockout")
    echo'<h2 style="padding-left: 350px">StockOut</h2>'
?>
    
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-lg-10" >
        <div class="well">
           
            <div class="row" id="createItem"> 
                <div class="single-row-stock">
                <div class="col-lg-3">
                    <div class="single-row-item">
                    <?= $form->field($model, 'ItemID')->widget(Select2::classname(), [
                      'data' => ArrayHelper::map(Items::find()->all(), 'ItemID', 'Name'),
                      'language' => 'en',
                      'options' => ['placeholder' => 'Select a Item ...','id'=>'Item'],
                      'pluginOptions' => [
                          'allowClear' => true
                      ],
                  ]);?>
                        </div>
                  </div>
                    <div class="col-lg-2">
                  <?= $form->field($model, 'Qty')->textInput() ?>

                      </div>
                      <div class="col-lg-2" >
                          <h2><label id="UnitId"></label></h2> 

                          </div>
                  
                 <?php if($val == "stockout"):  
                     $this->title = 'Stock Out';
                     $this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['/stock/stock/index']];
                     $this->params['breadcrumbs'][] = $this->title;
                     ?>
                <div class="col-lg-2" >
                 <?= $form->field($model, 'UserID')->widget(Select2::classname(), [
                      'data' => ArrayHelper::map(User::find()->all(), 'UserId', 'UserName'),
                      'language' => 'en',
                      'options' => ['placeholder' => 'User ...'],
                      'pluginOptions' => [
                          'allowClear' => true
                      ],
                  ]);?>
                </div>
                   <?php endif;  ?>
                </div>
                   
                </div>
                <?= $form->field($model, 'Remarks')->textarea(['rows' => 6]) ?>
                   
                <div class="form-group">
       <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                  </div>
            </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script=<<<JS
        
        $('#Item').change(function(){
        var ItemID=$(this).val();
        
        $.get('../items/getunit',{ItemID:ItemID},function(data){
        var data=$.parseJSON(data);
          
   $('#UnitId').html(data.Title);
   });
   
   });
  
   
JS;
$this->registerJs($script);
?>