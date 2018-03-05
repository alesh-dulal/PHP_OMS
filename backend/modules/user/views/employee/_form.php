<?php
  use yii\helpers\Html;
  use yii\widgets\ActiveForm;
  
  use kartik\select2\Select2;
  use kartik\date\DatePicker;

  use kartik\time\TimePicker;
  
  /* @var $this yii\web\View */
  /* @var $model backend\modules\user\models\Employee */
  /* @var $form yii\widgets\ActiveForm */
  ?>

<div class="employee-form">
  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
  <div class="panel panel-default">
    <div class="panel-heading">User Information</div>
    <div class="panel-body">
      <div class="col-lg-12">
        <div class="col-lg-2">
          <?php 
            $listData=['Select Salutation'=>'Select Salutation', 'Mr.'=>'Mr.', 'Ms.'=>'Ms.', 'Mrs.'=>'Mrs.'];
                        $options=
                            [
            
                            'Select Salutation' => ['disabled'=>true],
                            'Mr.' => ['label'=>'Mr.'],
                            'Ms.' => ['label' => 'Ms.'],
                            'Mrs.' => ['label' => 'Mrs.'],
                            ];
            
            echo $form->field($model, 'Salutation')->dropDownList($listData, ['options'=>$options])->label("Salutation * ");
                             ?>            
        </div>
        <div class="col-lg-5">
          <?= $form->field($model, 'FullName')->textInput(['maxlength' => true])->label("Name * ") ?>
        </div>
        <div class="col-lg-2">       
          <?php 
            $listData=['Select Gender'=>'Select Gender', 'Male'=>'Male', 'Female'=>'Female', 'Others'=>'Others'];
                        $options=
                            [
                            'Select Gender' => ['disabled'=>true],
                            'Male' => ['label'=>'Male'],
                            'Female' => ['label' => 'Female'],
                            'Others' => ['label' => 'Others'],
                            ];
            echo $form->field($model, 'Gender')->dropDownList($listData, ['options'=>$options])->label('Gender * ');
                             ?>
        </div>
        <div class="col-lg-3">
          <?= $form->field($model, 'DOB')->label('Birth Date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'yyyy-mm-dd',],
             'pluginOptions' => [
                 'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                 'autoclose'=>true,
                'endDate'=> '+0d',
            ]
            ])->label('DOB *');  ?> 
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-3">
          <?= $form->field($model, 'CellPhone')->textInput(['maxlength' => true])->label('CellPhone * ') ?>
        </div>
        <div class="col-lg-3">
          <?= $form->field($model, 'Email')->textInput()->label('Email * ') ?>
        </div>
        <div class="col-lg-3">
          <?= $form->field($model, 'MaritalStatus')->dropdownList(['1' => 'Married', '2' => 'Unmarried'], ['prompt' => 'Marital Status' ])->label('Marital Status *') ?>
        </div>
        <div class="col-lg-3">
          <?= $form->field($model, 'SpouseName')->textInput(['maxlength' => true]) ?>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Office Information</div>
    <div class="panel-body">
      <div class="col-lg-12">
        <div class="col-lg-3">
          <?php 
            echo $form->field($model, 'DepartmentID')->widget(Select2::classname(), [
                    'data' => $Department,
                    'language' => 'en',
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ])->label("Department * ");
            ?>
        </div>
        <div class="col-lg-3">
          <?php 

            echo $form->field($model, 'DesignationID')->widget(Select2::classname(), [
                    'data' => $Designation,
                    'language' => 'en',
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ])->label("Designation * ");
            ?>
        </div>
        <div class="col-lg-3">
          <?php 

            echo $form->field($model, 'RoleID')->widget(Select2::classname(), [
                    'data' => $Role,
                    'language' => 'en',
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ])->label("Role * ");
            ?>
        </div>
        <div class="col-lg-3">
          <?php 

            echo $form->field($model, 'RoomID')->widget(Select2::classname(), [
                    'data' => $Room,
                    'language' => 'en',
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ]) ->label("Room * ");
            ?>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-3">
          <?php 
            echo $form->field($model, 'BiometricID')->textInput()->label("Biometric Number * ");
            ?>
        </div>
        <div class="col-lg-3">
          <?php 

            echo $form->field($model, 'ShiftID')->widget(Select2::classname(), [
                    'data' => $Shift,
                    'language' => 'en',
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ]) ->label("Shift * ");
            ?>
        </div>
        <div class="col-lg-3">
          <?php 
            echo $form->field($model, 'Supervisor')->widget(Select2::classname(), [
                    'data' => $Supervisor,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select a Supervisor ...'],
                    'pluginOptions' => [
                            'allowClear' => true
                         ],
            ]) ->label("Supervisor * ");
            ?>
        </div>
        <div class="col-lg-3">
          <?= $form->field($model, 'Salary')->textInput() ?>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-4">
          <?= $form->field($model, 'HireDate')->label('Hire Date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'yyyy-mm-dd', 'value' => date('Y-m-d'),],
             'pluginOptions' => [
                 'format' => 'yyyy-mm-dd',
                 
                'todayHighlight' => true,
                 'autoclose'=>true,
            ]
            ]);  ?> 
        </div>
        <div class="col-lg-4">
          <?= $form->field($model, 'JoinDate')->label('Join Date')->widget(DatePicker::classname(), [
                 
            'options' => ['placeholder' => 'yyyy-mm-dd','value' => date('Y-m-d'),  ],
             'pluginOptions' => [
                 'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                 'autoclose'=>true,
            ]
            ]);  ?>   
        </div>
        <div class="col-lg-4">
          <?= $form->field($model, 'PromotedDate')->label('Promoted Date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'yyyy-mm-dd',],
             'pluginOptions' => [
                 'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                 'autoclose'=>true,
            ]
            ]);?>  
        </div>
        <div class="col-lg-12">
          <div class="col-lg-6">
            <?= $form->field($model, 'LoginTime')->widget(TimePicker::classname(),['pluginOptions' => ['showMeridian' => false]])->label("Punchin Time")?>
          </div>
          <div class="col-lg-6">
           <?= $form->field($model, 'LogoutTime')->widget(TimePicker::classname(),['pluginOptions' => ['showMeridian' => false]])->label("Punchout Time")?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Employee Contact and Address</div>
    <div class="panel-body">
      <div class="col-lg-12">
        <div class="col-lg-6">
          <?= $form->field($model, 'PermanantAddress')->textarea() ?>
        </div>
        <div class="col-lg-6">
          <?= $form->field($model, 'TemporaryAddress')->textarea() ?>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Emergency Contact</div>
    <div class="panel-body">
      <div class="col-lg-12">
        <div class="col-lg-4">
          <?= $form->field($model, 'EmergencyContact1Name')->textInput(['maxlength' => true])->label("Emergency Contact Name") ?>
        </div>
        <div class="col-lg-4">
          <?= $form->field($model, 'EmergencyContact1Relation')->textInput(['maxlength' => true])->label("Emergency Contact Relation") ?>
        </div>
        <div class="col-lg-4">
          <?= $form->field($model, 'EmergencyContact1Cell')->textInput(['maxlength' => true])->label("Emergency Contact Number") ?>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-4">
          <?= $form->field($model, 'EmergencyContact2Name')->textInput(['maxlength' => true])->label("Emergency Contact Name") ?>
        </div>
        <div class="col-lg-4">
          <?= $form->field($model, 'EmergencyContact2Relation')->textInput(['maxlength' => true])->label("Emergency Contact Relation") ?>
        </div>
        <div class="col-lg-4">
          <?= $form->field($model, 'EmergencyContact2Cell')->textInput(['maxlength' => true])->label("Emergency Contact Number") ?>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Insurance and Savings</div>
    <div class="panel-body">
      <div class="col-lg-12">
        <div class="col-lg-6">
          <?= $form->field($model, 'CitizenNumber')->textInput() ?>
        </div>
        <div class="col-lg-6">
          <div class="col-lg-12" id="citizenFile">
            <div class="col-lg-6">
          <?= $form->field($model, 'CitizenFile')->fileInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6" style="padding-left: 140px;">
              <!-- image here on update -->
              <?php 
            if (!$model->isNewRecord) {?>
        <p><img id="blah" src= <?php echo Yii::$app->urlManager->baseUrl."/uploads/citizenship/". $model->CitizenFile;?> height="150" width="150" /></p>
           <?php } else {?>
                    <p><img id="blah" src="https://dummyimage.com/150X150/000000/fff.jpg&text=citizenfile" height="150" width="150" /></p>
        <?php    }
           ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-6">
          <?= $form->field($model, 'CITNumber')->textInput() ?>
        </div>
        <div class="col-lg-6">
          <div class="col-lg-12" id="citFile">
            <div class="col-lg-6">
          <?= $form->field($model, 'CITFile')->fileInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6" style="padding-left: 140px;">
              <!-- image here on update -->
              <?php 
            if (!$model->isNewRecord) {?>
        <p><img id="blah" src= <?php echo Yii::$app->urlManager->baseUrl."/uploads/CIT/". $model->CITFile;?> height="150" width="150" /></p>
           <?php } else {?>
                  <p><img id="blah" src="https://dummyimage.com/150X150/000000/fff.jpg&text=citfile" height="150" width="150" /></p>
        <?php    }
           ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-6">
          <?= $form->field($model, 'PANNumber')->textInput() ?>
        </div>
        <div class="col-lg-6">
          <div class="col-lg-12" id="panFile">
            <div class="col-lg-6" >
          <?= $form->field($model, 'PANFile')->fileInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6" style="padding-left: 140px;">
              <!-- image here on update -->
              <?php 
            if (!$model->isNewRecord) {?>
        <p><img id="blah" src= <?php echo Yii::$app->urlManager->baseUrl."/uploads/PAN/". $model->PANFile;?> height="150" width="150" /></p>
           <?php } else {?>
                    <p><img id="blah" src="https://dummyimage.com/150X150/000000/fff.jpg&text=panfile" height="150" width="150" /></p>
        <?php    }
           ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-6">
        <?= $form->field($model, 'Insurance')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
           <?= $form->field($model, 'BankAccountNumber')->textInput(['maxlength' => true]) ?>
        </div>
      </div>
      <div class="col-lg-12" id="ProfileImage">
        <div class="col-lg-6">
          <?= $form->field($model, 'Image')->fileInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
        <!-- image here on update -->
          <?php 
            if (!$model->isNewRecord) {?>
        <p><img id="blah" src= <?php echo Yii::$app->urlManager->baseUrl."/uploads/profile/". $model->Image;?> height="150" width="150" /></p>
           <?php } else {?>
                    <p><img id="blah" src="https://dummyimage.com/150X150/000000/fff.jpg&text=Profile+Image" height="150" width="150" /></p>
        <?php    }
           ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12">
    <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']) ?>
  </div>
  <?php ActiveForm::end(); ?>
</div>
</div>

<?php 
  $js = <<< JS


function readURL(input, divname) {

  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      var name = $("div#"+divname);
      name.find('img').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$('div#citFile').find('input[name="Employee[CITFile]"]').change(function() {
  var divname = "citFile";
  readURL(this, divname);
});

$('div#panFile').find('input[name="Employee[PANFile]"]').change(function() {
  var divname = "panFile";
  readURL(this, divname);
});

$('div#citizenFile').find('input[name="Employee[CitizenFile]"]').change(function() {
  var divname = "citizenFile";
  readURL(this, divname);
});

$('div#ProfileImage').find('input[name="Employee[Image]"]').change(function() {
  var divname = "ProfileImage";
  readURL(this, divname);
});




function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        return true;
    }
};

$(document).ready(function(){
    $('div.container').find('input[name="Employee[CellPhone]"]').keypress(validateNumber);
});

JS;

$this->registerJS($js);
 ?>