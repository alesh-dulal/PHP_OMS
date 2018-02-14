<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Advance */

$this->title = $model->AdvanceID;
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->AdvanceID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->AdvanceID], [
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
            'AdvanceID',
            'EmployeeID',
            'Amount',
            'Rule',
            'CreatedDate',
            'CreatedBy',
            'UpdatedDate',
            'UpdatedBy',
            'IsActive',
            'IsDeleted',
        ],
    ]) ?>

</div>
