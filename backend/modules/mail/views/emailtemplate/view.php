<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\mail\models\Emailtemplate */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Emailtemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emailtemplate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->EmailTemplateID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->EmailTemplateID], [
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
            'EmailTemplateID:email',
            'Name',
            'Details:ntext',
            'CreatedBy',
            'CreatedDate',
            'UpdatedBy',
            'UpdatedDate',
        ],
    ]) ?>

</div>
