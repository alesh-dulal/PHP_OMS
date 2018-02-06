<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;

$this->title='View Report';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['/user/settings']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("table, th, td 
{
    margin:10px 0;
    border:solid 1px #333;
    padding:2px 4px;
    font:15px Verdana;
}
th {
    font-weight:bold;
}");


$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);

//print_r($model);
//die();
/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\Holiday */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-view">
<h1>View Report</h1>
    <?php $form = ActiveForm::begin(); ?>
<div>
<?= Html::a('CreateNewQuery', ['/report/report/index'], ['class' => 'btn btn-success']) ?>
</div>    
<div class="select-name" style="margin-top: 10px"> 
              <?php $htm='';
                foreach($model as $name):  ?> 
                <?php // print_r($name->Query); die();?>
              <?php $htm .= '<option value="'.$name->Query.'">'.$name->Name.'</option>';  ?>
                <?php endforeach; ?>
                    <select class="select-single"   >
                    <option value="">Select Name......</option>
                      <?= $htm ?>
                     </select>
            </div>
<!--<div class="row ">
    <div class="col-lg-5">
        <?php
//        echo '<label>From</label>';
//                echo DatePicker::widget([
//                        'name' => 'from', 
////                        'value' => date('Y-m-d', strtotime('-30 days')),
//                        'options' => ['placeholder' => 'Select from date ...'],
//                        'pluginOptions' => [
//                                'format' => 'yyyy-mm-dd',
//                                'todayHighlight' => true
//                        ]
//                ]);
                ?>
    </div>
       <div class="col-lg-5">
        <?php
//            echo '<label>To</label>';
//                echo DatePicker::widget([
//                        'name' => 'To', 
//                      //  'value' => date('Y-m-d'),
//                        'options' => ['placeholder' => 'Select to date'],
//                        'pluginOptions' => [
//                                'format' => 'yyyy-mm-dd',
//                                'todayHighlight' => true
//                        ]
//                ]);
                ?>
    </div>
</div>-->
<div style="padding-left: 450px">
<button type="button" class="btn btn-success get-report">Go</button>
</div>
<div id="data" >
         
         </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$script=<<<JS
         $(".select-single").select2();
                       
        
      $('button.get-report').click(function() {
        var from=$('#w1').val();
        var to=$('#w2').val();
        var daterange = from + 'to' + to;
        var name=$('div.select-name').find('select option:selected').val();
        
        getReport(daterange,name);
        
           });
    
         function getReport(daterange,name){
           $.ajax({
           url: 'getreport',
           type: 'post',
           data: {"daterange":daterange,"name":name},
           success: function (data) {
               var pdata= JSON.parse(data);
        // CREATE DYNAMIC TABLE.
                    var table = document.createElement("table");
        // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.
                    var tr = table.insertRow(-1);                   // TABLE ROW.
                    var len=pdata.record.length;
                      for(i=0;i<pdata.columns.length;i++)
                    {
                      var columns = pdata.columns[i];
                    
        
                      var th = document.createElement("th");      // TABLE HEADER.
                        th.innerHTML = pdata.columns[i];
                        tr.appendChild(th);
                    }
     
         
                    for (var i = 0; i < len; i++) {
                        tr = table.insertRow(-1);
        
                        for (var j = 0; j < pdata.columns.length; j++) {
                            var tabCell = tr.insertCell(-1);
                            tabCell.innerHTML = pdata.record[i][pdata.columns[j]];
                           
        
                        }
                                
                    }
                
                    $('div#data').html(table);
            }
          });
       }
        
        
JS;
$this->registerJs($script);
?>        