<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
  use kartik\select2\Select2;
  use kartik\date\DatePicker;

  use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Virtualemployee */
/* @var $form ActiveForm */
$this->title = 'Create Article Based Employee';
?>
<h4>Create Article Based Employee</h4>
<div class="create" id = "virtualEmployee">

    <?php $form = ActiveForm::begin(); ?>

        <div class="col-lg-4"><?= $form->field($model, 'FullName') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'Email') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'DOB')->label('Birth Date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'yyyy-mm-dd',],
             'pluginOptions' => [
                 'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                 'autoclose'=>true,
                'endDate'=> '+0d',
            ]
            ])->label('DOB *');  ?> </div>
        <div class="col-lg-4"><?= $form->field($model, 'CellPhone') ?></div>
        <div class="col-lg-4"><?php echo $form->field($model, 'Gender')->dropDownList(['male' => 'Male', 'female' => 'Female', 'others' => 'Others']); ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'HireDate')->label('Hire Date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'yyyy-mm-dd', 'value' => date('Y-m-d'),],
             'pluginOptions' => [
                 'format' => 'yyyy-mm-dd',
                 
                'todayHighlight' => true,
                 'autoclose'=>true,
            ]
            ]);  ?> </div>

        <div class="col-lg-4"><?php 
            echo $form->field($model, 'SupervisorID')->widget(Select2::classname(), [
                    'data' => $Supervisor,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select a Supervisor ...'],
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ]) ->label("Supervisor * ");
            ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'EmergencyContactName') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'EmergencyContactRelation') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'EmergencyContactCellPhone') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'PerArticle') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'BankAccountNumber') ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'Address')->textarea()->label('Address'); ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'Image')->fileInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4" id="image"><img id="blah" src="https://dummyimage.com/75X75/000000/fff.jpg&text=Profile" height="75" width="75" /></div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-right']) ?>
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