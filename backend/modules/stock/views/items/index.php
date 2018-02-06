<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['/stock/stock/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Add Items', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Name',
            
             [
                'attribute' => 'CategoryID',
                'value'     => function($data){
                              return (\backend\modules\user\models\Listitems::findOne($data->CategoryID))->Title;
                }
            ],
            
           
//            'IsActive',
            
            // 'InsertedDate',
            // 'UpdatedBy',
            // 'UpdateDate',
            // 'UnitID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
