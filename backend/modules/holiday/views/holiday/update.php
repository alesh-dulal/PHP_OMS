<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\holiday\models\Holiday */

$this->title = 'Update Holiday: ' .$model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Holidays', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->HolidayID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="holiday-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'year'=>$year,
    ]) ?>

</div>
