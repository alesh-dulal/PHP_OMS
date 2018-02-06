<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\mail\models\Sendmail */

$this->title = 'Update Sendmail: ' . $model->MailID;
$this->params['breadcrumbs'][] = ['label' => 'Sendmails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->MailID, 'url' => ['view', 'id' => $model->MailID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sendmail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
