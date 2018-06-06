<?php
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">
    <h1>
        <?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('StockIn', ['stockdetail/stockin'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('StockOut', ['stockdetail/stockout'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Damaged Entry', ['stockdetail/damage'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Expiring Stocks', ['stockdetail/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Userwise Stock', ['stockdetail/userindex'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Items Here', ['/stock/items/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'ItemID',
        'value'     => 'item.Name' //getComp()
    ],
    'Qty',
    // 'UpdatedBy',
    // 'UpdatedDate',
    // 'ItemID',
    ['class' => 'yii\grid\ActionColumn'],
];
// Renders a export dropdown menu
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'exportConfig' => [
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_EXCEL => false,
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_PDF => false,
    ],
    'filename' => 'Stock-export-list_' . date('Y-m-d_H-i-s'), 
]);
    ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'ItemID',
                    'value'     => 'item.Name' //getComp()
                ],
                'Qty',
            ],
        ]); 
    ?>
</div>
