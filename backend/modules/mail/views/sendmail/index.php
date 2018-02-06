<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\mail\models\SendmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sendmails';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sendmail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sendmail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'MailID',
            'Reciever',
            'Subject:ntext',
            'Message:ntext',
           // 'CreatedBy',
            // 'CreatedDate',
            // 'UpdatedBy',
            // 'UpdatedDate',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
