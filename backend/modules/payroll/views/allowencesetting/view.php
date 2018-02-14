<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Allowencesetting */

$this->title = $model->Title;
$this->params['breadcrumbs'][] = ['label' => 'Allowencesettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allowencesetting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->AllowenceSettingID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->AllowenceSettingID], [
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
            'AllowenceSettingID',
            'IsAllowence',
            'Title',
            'Amount',
            'Formula',
            'CreatedDate',
            'CreatedBy',
            'UpdatedDate',
            'UpdatedBy',
            'IsActive',
            'IsDeleted',
        ],
    ]) ?>

</div>
