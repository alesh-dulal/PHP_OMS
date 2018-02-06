<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\attendance\models\Year;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\holiday\models\HolidaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Holidays';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['/user/settings']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Holiday', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'HolidayID',
            'Name',
            'Description:ntext',
            'Day',
//            'Year',
               [
                'attribute' => 'Year',
                'value'     => function($data){
                                $yearModel =  Year::findOne($data->Year);
                                return $yearModel['Name'];
                }
            ],
            // 'IsActive',
            // 'InsertedBy',
            // 'InsertedDate',
            // 'UpdatedBy',
            // 'UpdatedDate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
