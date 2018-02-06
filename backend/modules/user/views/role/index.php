<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['/user/settings']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
            Modal::begin([
                'header' => 'Create Role',
                'id' => 'modal',
                'size' => 'modal-lg',  
            ]);

            echo "<div id='modalContent'></div>";

            Modal::end();
        ?>


<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Create Role', ['value'=>Url::to(['role/create']),'class' => 'btn btn-success', 'id'=>'modalButton']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],

            'RoleID',
            'Name',
            'MenuID',
           // 'CreatedDate',
          //  'CreatedBy',
            // 'UpdatedDate',
            // 'UpdatedBy',
            // 'IsActive',
            // 'IsDeleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>


<?php 
$js = <<< JS

$('#modalButton').click(function(){
    $('#modal').modal('show').find('#modalContent').load($(this).attr('value'));
});
JS;

$this->registerJS($js);
 ?>