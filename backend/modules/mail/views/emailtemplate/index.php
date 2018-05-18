<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\mail\models\EmailtemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'EmailTemplates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emailtemplate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Create EmailTemplate', ['value'=> \yii\helpers\Url::to(['emailtemplate/create']),'class' => 'btn btn-success','id'=>'modalButton']) ?>
    </p>

    <?php
    //Popup Window
            Modal::begin([
                'id'=>'modal',
                
            ]);
           //$myModel = new \backend\models\Appointment;
            //echo $this->render('create', ['model' => $myModel]);
            
            echo "<div id='modalContent'></div>";           
            
            Modal::end();
        ?>
  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'EmailTemplateID:email',
            'Name',
            'Details:ntext',
//            'CreatedBy',
//            'CreatedDate',
            // 'UpdatedBy',
            // 'UpdatedDate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>


<?php
$script=<<<JS

$(function(){
   $('#modalButton').click(function(){
       $('#modal').modal('show')
               .find('#modalContent')
               .load($(this).attr('value'));
   });
});
JS;
$this->registerJs($script);
?>

