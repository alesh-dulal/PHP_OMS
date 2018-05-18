<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Stockdetail */

$this->title = $model->StockDetailID;
$this->params['breadcrumbs'][] = ['label' => 'Stockdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockdetail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->StockDetailID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->StockDetailID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'Qty',
            'Remarks',
            
             [
            'label'=>'Unit',
            'attribute'=>'unit.Name'
            ],
            
            
              [
            'label'=>'Inserted Date',
            'attribute'=>'InsertedDate'
            ],
            'item.ItemID',
              [
                'attribute' => 'ItemID',
                'value'     => function($data){
                                $itemModel =  \backend\models\Items::findOne($data->ItemID);
                                return $itemModel->Name;
                }
            ],
            [
                'attribute' => 'UserID',
                'value'     => function($data){
                                $UserModel = \backend\models\User::findOne($data->UserID);
                                return $UserModel->username;
                }
            ],
            'ExpiryDate',
        ],
    ]) ?>

</div>
