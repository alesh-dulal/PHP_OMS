<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\payroll\models\Allowencesetting */

$this->title = 'Update Allowencesetting: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Allowencesettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Title, 'url' => ['view', 'id' => $model->AllowenceSettingID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allowencesetting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
