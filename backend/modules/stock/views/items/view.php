<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\user\models\Listitems;
/* @var $this yii\web\View */
/* @var $model backend\models\Items */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ItemID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ItemID], [
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
           'Name',
           [
                'attribute'=>'CategoryID',
                'label'=>'Category',
                'value'=>function($data){
                    $DataValue = $data['CategoryID'];
                    $Category =  Listitems::find()->where(['ListItemID'=>$DataValue])->one();
                    return $Category['Title'];
                }
           ],

        ],
    ]) ?>

</div>
