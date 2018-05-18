<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\modules\stock\models\Items;
use backend\modules\user\models\User;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StockdetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'UserwiseStock';
$this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['/stock/stock/index']];
$this->params['breadcrumbs'][] = $this->title;

//print_r($dataProvider);
//die();
?>
<div class="stockdetail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

       <?php
     $gridColumns = [
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
                    
              [
                'attribute' => 'UserID',
                'value'     => function($data){
                                $usermodel =  User::findOne($data->UserID);
                                if($usermodel):
                                    return $usermodel->UserName;
                                else:
                                    return "NULL";
                                endif;
                }
            ],
   
            ['label'=>'Taken Date',
                'attribute' => 'CreatedDate',],
             
               
        ];

        // Renders a export dropdown menu
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'exportConfig' => [
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_EXCEL => false,
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_PDF => false,
    ],
            'filename' => 'User-export-list_' . date('Y-m-d_H-i-s'),    
        ]);
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'ItemID',
                'value'     => function($data){
                        $itemModel =  Items::findOne($data->ItemID);
                        return $itemModel['Name'];
                    }
            ],

            'Qty',

            'Remarks',

            [
                'attribute' => 'UserID',
                'value'     => function($data){
                        $usermodel =  User::findOne($data->UserID);
                        if($usermodel):
                            return $usermodel->UserName;
                        else:
                            return "NULL";
                        endif;
                    }
            ],

            [
                'attribute' => 'CreatedDate',
                'label'=>'Taken Date',
            ],

            ],
    ]);
            
            ?>
   
</div>
