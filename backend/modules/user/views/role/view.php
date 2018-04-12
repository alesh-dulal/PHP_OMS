<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Role */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->RoleID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->RoleID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        
        <?= Html::button('Communication', ['value'=>Url::to(['role/communication']),'class' => 'btn btn-success', 'id'=>'modalButton']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'RoleID',
            'Name',
            'MenuID',
            'CreatedDate',
            'CreatedBy',
            'UpdatedDate',
            'UpdatedBy',
            'IsActive',
            'IsDeleted',
        ],
    ]) ?>

</div>
