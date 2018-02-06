<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employee */

$this->title = $model->FullName;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->EmployeeID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->EmployeeID], [
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
                              return (\backend\modules\user\models\Listitems::findOne($data->DepartmentID))->Title;
                }
            ],
            
            [
                'attribute' => 'DesignationID',
                'label' => 'Designation',
                 'value'     => function($data){
                              return (\backend\modules\user\models\Listitems::findOne($data->DesignationID))->Title;
                }
            ],

            [
                'attribute' => 'RoleID',
                'label' => 'Role',
                 'value'     => function($data){
                              return (\backend\modules\user\models\Role::findOne($data->RoleID))->Name;
                }
            ],
 // 'BiometricID',

             [
                'attribute' => 'ShiftID',
                'label' => 'Shift',
                 'value'     => function($data){
                              return (\backend\modules\user\models\Listitems::findOne($data->ShiftID))->Title;
                }
            ],  

            [
                'attribute' => 'Supervisor',
                'label' => 'Supervisor Name',
                 'value'     => function($data){

                   return \backend\modules\user\models\Employee::findOne($data->Supervisor)->FullName;
                }
            ],


            'DOB',
            'Email:email',
            'CellPhone',
            // 'PermanantAddress',
            // 'TemporaryAddress',
            // 'HireDate',
            // 'JoinDate',
            // 'PromotedDate',
            // 'MaritalStatus',
            // 'SpouseName',
            // 'EmergencyContact1Name',
            // 'EmergencyContact1Relation',
            // 'EmergencyContact1Cell',
            // 'EmergencyContact2Name',
            // 'EmergencyContact2Relation',
            // 'EmergencyContact2Cell',
            // 'Ethnicity',
            // 'Religion',
            // 'CitizenNumber',
            // 'CitizenFile',
            // 'Insurance',
            // 'CITNumber',
            // 'CITFile',
            // 'PANNumber',
            // 'PANFile',
            // 'CreatedDate',
            // 'CreatedBy',
            // 'UpdatedDate',
            // 'UpdatedBy',
            // 'IsActive',
            // 'IsDeleted',
        ],
    ]) ?>

</div>
