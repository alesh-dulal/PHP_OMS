<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Listitems */

$this->title = 'Update Listitems: ' . $model->Title;
$this->params['breadcrumbs'][] = ['label' => 'Listitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Title, 'url' => ['view', 'id' => $model->ListItemID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="listitems-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
