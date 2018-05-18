<?php
use yii\helpers\Html;
use yii\grid\GridView;

use backend\modules\user\models\Listitems;
use backend\modules\user\models\Employee;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = 'Terminated Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<h4 align="center">Terminated Employees</h4>
<?php
     $gridColumns = [
                           
            [
                'attribute' => 'FullName',
                'label' => 'Name',
                'value'     =>function($data){
                    return $data->Salutation." ".$data->FullName;
                },
            ],

            [
                'attribute' => 'DepartmentID',
                'label' => 'Department',
                 'value'     => function($data){
                              return \backend\modules\user\models\Listitems::findOne($data->DepartmentID)->Title;
                }
            ],
            
            [
                'attribute' => 'DesignationID',
                'label' => 'Designation',
                 'value'     => function($data){
                              return \backend\modules\user\models\Listitems::findOne($data->DesignationID)->Title;
                }
            ],

            [
                'attribute' => 'RoleID',
                'label' => 'Role',
                 'value'     => function($data){
                              return \backend\modules\user\models\Role::findOne($data->RoleID)->Name;
                }
            ],
 // 'BiometricID',

             [
                'attribute' => 'ShiftID',
                'label' => 'Shift',
                 'value'     => function($data){
                              return \backend\modules\user\models\Listitems::findOne($data->ShiftID)->Title;
                }
            ],  

            [
                'attribute' => 'Supervisor',
                'label' => 'Supervisor Name',
                 'value'     => function($data){

                  // return \backend\modules\user\models\Employee::findOne($data->Supervisor)->FullName;
                }
            ],


            'DOB',
            'Email:email',
            'CellPhone',
            
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
             'filename' => 'Employee-export-list_' . date('Y-m-d_H-i-s'), 
        ]);
    ?>
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'DepartmentID',

            [
                'attribute' => 'FullName',
                'label' => 'Name',
                'value'     =>function($data){
                    return $data->Salutation." ".$data->FullName;
                },
            ],

            [
                'attribute' => 'DepartmentID',
                'label' => 'Department',
                 'value'     => function($data){
                              return \backend\modules\user\models\Listitems::findOne($data->DepartmentID)->Title;
                }
            ],
            
            [
                'attribute' => 'DesignationID',
                'label' => 'Designation',
                 'value'     => function($data){
                              return \backend\modules\user\models\Listitems::findOne($data->DesignationID)->Title;
                }
            ],

            [
                'attribute' => 'RoleID',
                'label' => 'Role',
                 'value'     => function($data){
                              return \backend\modules\user\models\Role::findOne($data->RoleID)->Name;
                }
            ],
 // 'BiometricID',

             [
                'attribute' => 'ShiftID',
                'label' => 'Shift',
                 'value'     => function($data){
                              return \backend\modules\user\models\Listitems::findOne($data->ShiftID)->Title;
                }
            ],  

            [
                'attribute' => 'Supervisor',
                'label' => 'Supervisor Name',
                 'value'     => function($data){

                  return \backend\modules\user\models\Employee::findOne($data->Supervisor)->FullName;
                }
            ],



            'Email:email',
            'CellPhone',           
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {link}',
                    'buttons' => [
					'link' => function ($url,$model,$key) {
					return Html::a(
							'<span class="rejoin"></span>',
							['employee/rejoin', 'id' => $model->EmployeeID], 
							[
								'title' => 'Rejoin',
								'aria-label'=>"Rejoin",
								'data-pjax' => '0',
							]
							);
						},
					],
                ],
            ]
    ]); ?>
