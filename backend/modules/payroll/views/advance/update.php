<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Advance */

$this->title = 'Update Advance: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AdvanceID, 'url' => ['view', 'id' => $model->AdvanceID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advance-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
