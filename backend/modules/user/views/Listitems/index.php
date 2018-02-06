<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\ListitemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listitems-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Listitems', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ListItemID',
            'Type',
            'Title',
            'Value',
            'IsParent',
            // 'Options',
            // 'CreatedDate',
            // 'CreatedBy',
            // 'UpdatedDate',
            // 'UpdatedBy',
            // 'IsActive',
            // 'IsDeleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
