

<?php
  use kartik\select2\Select2;
  use yii\helpers\Html;
  use yii\widgets\ActiveForm;
  use backend\modules\user\models\Employee;
  use backend\modules\user\models\Role;
  use kartik\date\DatePicker;

  $this->title = "Attendance";
?>

<div class="row" id="searching">
  <div class="col-lg-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-3">
      <?php 
         $LoggedInEmployeeRole = Yii::$app->session['Role'];
        if(strtolower($LoggedInEmployeeRole) == "employee"||strtolower($LoggedInEmployeeRole) == "trainee"):
          echo '<h3 id="currentLoggedIn" data-employeeID='.Yii::$app->session['EmployeeID'].'>'.Yii::$app->session['FullName'].'</h3>';
        else:
            $model->EmployeeID=Yii::$app->user->id;  
            echo $form->field($model, 'EmployeeID')->widget(Select2::classname(), [
                       'data' => Yii::$app->empList->listEmployee(),
                       'language' => 'en',
                       'options' => ['placeholder' => 'Select Employee  ...'],
                       'pluginOptions' => [
                               'allowClear' => true
                            ],
               ]);
        endif;
       ?>
    </div>
    <div class="col-lg-3">

      <?php
            echo '<label>From</label>';
                echo DatePicker::widget([
                        'name' => 'from', 
                        'value' => date('Y-m-d', strtotime('-30 days')),
                        'options' => ['placeholder' => 'Select from date ...'],
                        'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                        ]
                ]);
                ?>
      
    </div>
    <div class="col-lg-3">
       <?php
            echo '<label>To</label>';
                echo DatePicker::widget([
                        'name' => 'To', 
                        'value' => date('Y-m-d'),
                        'options' => ['placeholder' => 'Select to date'],
                        'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                        ]
                ]);
                ?>
    </div>
    <div class="col-lg-3 button-find">

      <?php echo Html::button('Find', ['data-employeeID'=>Yii::$app->session['EmployeeID'], 'class' => 'btn btn-success attendance-go', 'data-id'=>'findo']); ?>
    </div>
  </div>
  <?php ActiveForm::end(); ?>
</div>
<br>
<div class="row cool-data">
  <table id='attendanceDetail' class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">AttendanceDate</th>
      <th scope="col">CheckInTime</th>
      <th scope="col">CheckOutTime</th>
      <th scope="col">CheckInDiff</th>
      <th scope="col">CheckOutDiff</th>
      <th scope="col">WorkedTime</th>
      <th scope="col">WorkedTimeDiff</th>
      <th scope="col">Remarks</th>
    </tr>
  </thead>
  <tbody>
    <!--Search Results displays here-->
  </tbody>
</table>
</div>
<?php 
$js = <<< JS



var from=$('#w1').val();
           var to=$('#w2').val();
           var employee = $('button.attendance-go').attr('data-employeeID');
           var data = from + 'to' + to;
           loadData(employee,data);
           
       $('div#searching').find('div.button-find button').click(function() {
        $('table#attendanceDetail').find('tbody').html('');
           var from=$('#w1').val();
           var to=$('#w2').val();
           
           var data = from + 'to' + to;
   
           loadData($(this).attr('data-employeeID'),data);
         });
           
         $('#attendance-employeeid').on('select2:select', function (e) {
           var data = e.params.data.id;
           $('button.attendance-go').attr('data-employeeID',data);
            });
         
           function loadData(employee,data){
              $.ajax({
              url: 'find',
              type: 'post',
              data: {data:data,employee:employee},
              success: function (data) {
                $('table#attendanceDetail').find('tbody').html(data);
              }//closing of success function
             });
           
           }
JS;

$this->registerJS($js);

?>