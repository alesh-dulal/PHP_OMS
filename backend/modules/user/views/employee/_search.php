<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\EmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'EmployeeID') ?>

    <?= $form->field($model, 'DepartmentID') ?>

    <?= $form->field($model, 'DesignationID') ?>

    <?= $form->field($model, 'RoleID') ?>

    <?= $form->field($model, 'RoomID') ?>

    <?php // echo $form->field($model, 'BiometricID') ?>

    <?php // echo $form->field($model, 'ShiftID') ?>

    <?php // echo $form->field($model, 'UserID') ?>

    <?php // echo $form->field($model, 'Salutation') ?>

    <?php // echo $form->field($model, 'FullName') ?>

    <?php // echo $form->field($model, 'Gender') ?>

    <?php // echo $form->field($model, 'DOB') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'CellPhone') ?>

    <?php // echo $form->field($model, 'PermanantAddress') ?>

    <?php // echo $form->field($model, 'TemporaryAddress') ?>

    <?php // echo $form->field($model, 'HireDate') ?>

    <?php // echo $form->field($model, 'JoinDate') ?>

    <?php // echo $form->field($model, 'PromotedDate') ?>

    <?php // echo $form->field($model, 'MaritalStatus') ?>

    <?php // echo $form->field($model, 'SpouseName') ?>

    <?php // echo $form->field($model, 'EmergencyContact1Name') ?>

    <?php // echo $form->field($model, 'EmergencyContact1Relation') ?>

    <?php // echo $form->field($model, 'EmergencyContact1Cell') ?>

    <?php // echo $form->field($model, 'EmergencyContact2Name') ?>

    <?php // echo $form->field($model, 'EmergencyContact2Relation') ?>

    <?php // echo $form->field($model, 'EmergencyContact2Cell') ?>

    <?php // echo $form->field($model, 'Ethnicity') ?>

    <?php // echo $form->field($model, 'Religion') ?>

    <?php // echo $form->field($model, 'CitizenNumber') ?>

    <?php // echo $form->field($model, 'CitizenFile') ?>

    <?php // echo $form->field($model, 'Insurance') ?>

    <?php // echo $form->field($model, 'CITNumber') ?>

    <?php // echo $form->field($model, 'CITFile') ?>

    <?php // echo $form->field($model, 'PANNumber') ?>

    <?php // echo $form->field($model, 'PANFile') ?>

    <?php // echo $form->field($model, 'CreatedDate') ?>

    <?php // echo $form->field($model, 'CreatedBy') ?>

    <?php // echo $form->field($model, 'UpdatedDate') ?>

    <?php // echo $form->field($model, 'UpdatedBy') ?>

    <?php // echo $form->field($model, 'IsActive') ?>

    <?php // echo $form->field($model, 'IsDeleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
