<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\modules\stock\models\Items;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StockdetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expiring Stocks';
$this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['/stock/stock/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockdetail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //show color in expire date start within 10 days
        'rowOptions'=>function($model){
        $expirydate=$model->ExpiryDate;
        $currentdate=date("Y-m-d");
        if($expirydate!=NULL){
            $diff=strtotime($expirydate)-strtotime($currentdate);
            $days = $diff / 60 / 60 / 24;
            //  print_r($days);die();
        if($days<=10){
            return['class'=>'danger'];
        }
        }
      },
        //end of xpiry date color
              
       'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             [
                'attribute' => 'ItemID',
                'value'     => function($data){
                                $itemModel =  Items::findOne($data->ItemID);
                                return $itemModel->Name;
                }
            ],
            'Qty',
            'Remarks',
//            'IsStock',

            //  ['label'=>'Unit',
            //     'attribute' => 'UnitID',
            //     'value'     => 'unit.Name' //getComp()
            // ],
            
            // 'IsActive',
            // 'InsertedBy',
            ['label'=>'Added Date',
                'attribute' => 'CreatedDate',],
             
            // 'UpdatedBy',
            // 'UpdatedDate',
            // 'ItemID',
            // 'UserID',
             'ExpiryDate',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
   
</div>
