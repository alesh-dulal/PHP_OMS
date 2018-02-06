<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\mail\models\Sendmail */

$this->title = 'Create Sendmail';
$this->params['breadcrumbs'][] = ['label' => 'Sendmails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sendmail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
