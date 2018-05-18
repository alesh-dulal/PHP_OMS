<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Stockdetail */

$this->title = 'Create Stock';
$this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockdetail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
