<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title='Report';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['/user/settings']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile("@web/backend\web\css\select2.min.css");
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

$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);


/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\Holiday */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">
    <div>
<?= Html::a('ViewReport', ['/report/report/view'], ['class' => 'btn btn-success']) ?>
</div>  
    <h1>Report</h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="well col-lg-10" style="margin-top: 10px">
            <div class="row">
            <div class="col-lg-6">
            <label>Query</label>
            <textarea class="form-control" id="query">select * from stock</textarea>
            </div>
            <div class="col-lg-2" style="margin-top: 20px">
            <button type="button" class="btn btn-success requested-report">Go</button>
            </div>
         </div>  
            
       <div class="row">
           <div class="col-lg-6">
                <label>Name</label>
                   <input type="text" class="form-control" id="name" >
           </div>      
           <div class="col-lg-3" style="margin-top: 20px">  
                   <label>DateBetweenEnabled</label>
                   <input type="checkbox"  id="dateRange" value="">
                   </div>
                <div id="selectColumn" style="margin-top: 20px">   

                     <select  class="select-single"  id="selectSingleColumn">
                         <option >Select Column</option>
                     </select>
                </div>
        </div>     
     
         <div style="margin-top: 10px">
         <button type="button" class="btn btn-success save-query">Save</button> 
         </div>
         <div id="data" >
         
         </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
     

<?php
$script=<<<JS
      
//save all in the form
          $('button.save-query').click(function() {
          var daterange= $('input#dateRange').prop('checked');
          var query=$('textarea#query').val();
          var name=$('input#name').val();
          var column=$('select#selectSingleColumn').find(":selected").text();
          saveData(daterange,query,name,column);
         
     });
    
//Ajax for save
   function saveData(daterange,query,name,column){
           $.ajax({
           url: 'save',
           type: 'post',
           data: {"daterange":daterange,"query":query,"name":name,"column":column},
           success: function (data) {
              console.log(data);
            }
          });
       }

        //query
     $('button.requested-report').click(function() {
          var query=$('textarea#query').val();
          loadData(query);
         
     });
      function loadData(query){
           $.ajax({
           url: 'retrieve',
           type: 'post',
           data: {"query":query},
           success: function (data) {
                   var pdata= JSON.parse(data);
        // CREATE DYNAMIC TABLE.
                    var table = document.createElement("table");
        // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.
                    var tr = table.insertRow(-1);                   // TABLE ROW.
                    var option='<option >Select Column</option>';
                    
                    var len=pdata.record.length;
                      for(i=0;i<pdata.columns.length;i++)
                    {
                      var columns = pdata.columns[i];
                    option+='<option value="'+[i]+'">'+columns+'</option>';
        
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
        
                    $('div#selectColumn').find('select#selectSingleColumn').html(option);
                    $('div#data').html(table);
              }
           
          });
        
        }
        
JS;
$this->registerJs($script);
?>  


                
                      
            


 