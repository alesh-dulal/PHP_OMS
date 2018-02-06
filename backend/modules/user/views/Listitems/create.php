<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Listitems */

$this->title = 'Create Listitems';
$this->params['breadcrumbs'][] = ['label' => 'Listitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listitems-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
