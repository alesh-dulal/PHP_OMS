<?php
use yii\helpers\Html;
use yii\grid\GridView;

use backend\modules\user\models\Listitems;
use backend\modules\user\models\Virtualemployee;
use backend\modules\user\models\VirtualemployeeSearch;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\VirtualEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Virtual Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Create Virtual Employee', ['virtualemployee/create'], ['class'=>'btn btn-success']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'FullName',
                'label' => 'Name',
                'value'     =>function($data){
                    return $data->FullName;
                },
            ],

            [
                'attribute' => 'SupervisorID',
                'label' => 'Supervisor Name',
                 'value'     => function($data){
                  return \backend\modules\user\models\Employee::findOne($data->SupervisorID)->FullName;
                }
            ],

            'Email:email',
            'CellPhone',
            'Address',
            'PerArticle',
            
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update}',
                ],
            ]
    ]); ?>
</div>