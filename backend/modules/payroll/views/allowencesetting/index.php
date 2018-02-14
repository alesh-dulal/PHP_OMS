<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\payroll\models\AllowencesettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Allowencesettings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allowencesetting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Allowencesetting', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label'=>'Allowence and Deduction',
                'value' => function($data){
                    return $data->IsAllowence == 1 ? 'Allowence' : 'Deduction';
                }
            ],
            'Title',
            'Amount',
            'Formula',
            'CreatedDate',
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
