<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Stockdetail */

$this->title = 'Update Stockdetail: ' . $model->StockDetailID;
$this->params['breadcrumbs'][] = ['label' => 'Stockdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->StockDetailID, 'url' => ['view', 'id' => $model->StockDetailID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stockdetail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
