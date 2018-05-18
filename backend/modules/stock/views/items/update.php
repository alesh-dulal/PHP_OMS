<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Items */

$this->title = 'Update Items: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ItemID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
         'Unit' => $Unit,
                'Category' => $Category,
    ]) ?>

</div>
