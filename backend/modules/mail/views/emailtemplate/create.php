<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\mail\models\Emailtemplate */

$this->title = 'Create EmailTemplate';
$this->params['breadcrumbs'][] = ['label' => 'Emailtemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emailtemplate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
