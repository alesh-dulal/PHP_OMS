<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Virtualemployee */
/* @var $form ActiveForm */
?>
<h4>Create Article Based Employee</h4>
<div class="Update" id="virtualEmployee">

    <?php $form = ActiveForm::begin(); ?>

        <div class="col-lg-4"><?= $form->field($model, 'FullName') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'Email') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'DOB')->label('DOB') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'CellPhone') ?></div>
        <div class="col-lg-4"><?php echo $form->field($model, 'Gender')->dropDownList(['male' => 'Male', 'female' => 'Female', 'others' => 'Others']); ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'HireDate') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'SupervisorID') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'EmergencyContactName') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'EmergencyContactRelation') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'EmergencyContactCellPhone') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'PerArticle') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'BankAccountNumber') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'Address')->textarea()->label('Address'); ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'Image')->fileInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4" id="image"><img id="blah" src= <?php echo Yii::$app->urlManager->baseUrl."/uploads/profile/". $model->Image;?> height="75" width="75" /></div>
        
        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-info pull-right']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- create -->
<?php 
  $js = <<< JS
function readURL(input, divname) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var name = $("div#"+divname);
      console.log(name);
      name.find('img').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$('div#virtualEmployee').find('input[name="Virtualemployee[Image]"]').change(function() {
  var divname = "image";
  readURL(this, divname);
})
JS;
$this->registerJS($js);
?>