<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Listitems */

$this->title = $model->Title;
$this->params['breadcrumbs'][] = ['label' => 'Listitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listitems-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ListItemID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ListItemID], [
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
            'ListItemID',
            'Type',
            'Title',
            'Value',
            'IsParent',
            'Options',
            'CreatedDate',
            'CreatedBy',
            'UpdatedDate',
            'UpdatedBy',
            'IsActive',
            'IsDeleted',
        ],
    ]) ?>

</div>
