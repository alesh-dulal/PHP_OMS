<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\mail\models\Emailtemplate */

$this->title = 'Update Emailtemplate: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Emailtemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->EmailTemplateID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="emailtemplate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
