<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



use dosamigos\tinymce\TinyMce;
use backend\modules\stock\models\Items;

$this->title = 'Stock In';
$this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['/stock/stock/index']];
$this->params['breadcrumbs'][] = $this->title;


/* @var $this yii\web\View */
/* @var $model backend\models\Stockdetail */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="stockdetail-form">
    <h2 style="padding-left: 400px">StockIn</h2>
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-lg-10">
        <div class="well">
            <div style="padding-left: 400px">
                <button type="button" class="btn btn-primary grid-button" id="addItem">Add Item</button>
            </div>
            <?php 
            $htm='';
            foreach(Items::find()->all() as $item):  ?> 
                <?php $htm .=  '<option value='.$item->ItemID.'>'.$item->Name.'</option>';  ?>
            
            <?php endforeach; ?>
            
            <div id="stockLists" data-item-list="<?=$htm?>">
                
            <div class="row single-row-stock" > 
               
                <div class="col-lg-3"> 
                    <label>Item</label><br>
                    <select class="select-single"  name="Stockdetail[ItemID]" >
                    <option value="">Select Item</option>
                    <?= $htm ?>
                        </select>
                         
                  </div>
                      <div class="col-lg-2">
                  <div class="form-group field-stockdetail-qty has-success">
                        <label class="control-label" for="stockdetail-qty">Qty</label>
                        <input type="text"  class="form-control qty" name="Stockdetail[Qty]" aria-invalid="false">
                   </div>
                        
                    </div>
                      <div class="col-lg-2" >
                          <h2><label class="unit" name="Stockdetail[UnitID]" data-id ="0"></label></h2> 
                      </div>
                    
                 <div class="col-lg-3" >
                    <label>Expiry Date</label><br>
                    <input type="date"  class="expiryDate"  name="Stockdetail[ExpiryDate]">
                 </div>
          
              </div>
        </div>
             <?= $form->field($model, 'Remarks')->textarea(['rows' => 6]) ?>
                 
            <div class="form-group" style="padding-left:400px">
       <?php //echo Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id'=>'addButton']) ?>
                  </div>
            
            <button type="button" id="addButton" style="margin-left: 400px" class="btn btn-primary">Save</button>
            </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script=<<<JS
      $(document).ready(function(){
        $('div#stockLists').on('change' ,'select',function(){
          
        var ItemID=$(this).val();
        var ele=$(this);
        
        $.get('../items/getunit',{ItemID:ItemID},function(data){
        var data=$.parseJSON(data);
  
        ele.parents('div.single-row-stock').first().find('label.unit').text(data.Title);
        ele.parents('div.single-row-stock').first().find('label.unit').attr('data-id', data.ListItemID);
   
   });
        });
        $(".select-single").select2();
        
           $('div#stockLists').on('click' ,'button.remove',function(){
           $(this).parents('div.single-row-stock').first().remove();
         });
        });
  
        $("#addItem").click(function () {
        var html=''; 
        html+='<div class="row single-row-stock" > '
        html+='<div class="col-lg-3">'
        html+='<label>Item</label><br>'
        html+=' <select class="select-single" name="Stockdetail[ItemID]">'
        html+='<option value="">Select Item</option>'
        html+=$('div#stockLists').attr('data-item-list');
        html+=' </select>'
        html+='  </div>'
        
        html+='  <div class="col-lg-2">'
        html+='  <div class="form-group field-stockdetail-qty has-success">'
        html+='  <label class="control-label" for="stockdetail-qty">Qty</label>'
        html+='  <input type="text"  class="form-control qty" name="Stockdetail[Qty]" aria-invalid="false">'
        html+='   </div>'
        html+='   </div>'
        
        html+='   <div class="col-lg-2">'
        html+='   <h2><label class="unit" name="Stockdetail[UnitID]" value=""></label></h2> '
        html+='    </div> '
        
        html+='    <div class="col-lg-3">'
        html+='    <label>Expiry Date</label><br>'
        html+='    <input type="date" class="expiryDate" name="Stockdetail[ExpiryDate]">'
        html+='      </div>'
        
        html+='<button type="button" name="remove" class="btn btn-danger btn-sm remove glyphicon glyphicon-minus" style="margin-top:25px"></button>'
       
        html+='</div>'
        html+='</div>';
        
         var append=$('div#stockLists').append(html);
          $('div#stockLists').find('div.single-row-stock').last().find('select').select2();

        
      });

$('#addButton').click(function(){      
  var itemID = [] ;
  var Qty = [];
  var exDate = [];
  var un = [];
  $('.select-single').each(function(){
    itemID.push($(this).val());
  });

  $('.qty').each(function(){
    Qty.push($(this).val());
  });
                
  $('.expiryDate').each(function(){
    exDate.push($(this).val());
  });
      
  $('label.unit').each(function(){
    un.push($(this).attr('data-id'));
  });

data={};

data['itemID'] = itemID;
data['Qty'] = Qty;
data['exDate'] = exDate;
data['un'] = un;
data['remarks']=$('#stockdetail-remarks').val();

  $.ajax({   
      type: 'POST',  
      url: 'multisave',  
      cache: false,  
      data: data,
      success: function(data)  
          {  

          }   
      });
 
});
JS;
$this->registerJs($script);
?>