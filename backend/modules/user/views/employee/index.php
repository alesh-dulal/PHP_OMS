<?php
use yii\helpers\Html;
use yii\grid\GridView;

use backend\modules\user\models\Listitems;
use backend\modules\user\models\Employee;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>

<p id="successMsg" style="display:none">Email Send.</p>

<?php if (Yii::$app->session->hasFlash('fileextensionerror')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Error!!</h4>
    <?= Yii::$app->session->getFlash('fileextensionerror') ?>
  </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('importsucceed')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Import Success!!!</h4>
    <?= Yii::$app->session->getFlash('importsucceed') ?>
  </div>
<?php endif; ?>

<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
                    
                </div>
                <!-- <div class="col-lg-6 excelimport">
                    <input type="file" name="ExcelFile" id="ExcelFile"/>
                    <button id="upload" name="uploadfile" class="btn btn-primary" value="Upload">Export</button>
                </div> -->
            </div>
    </div>
    
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

                  // return \backend\modules\user\models\Employee::findOne($data->Supervisor)->FullName;
                }
            ],



            'Email:email',
            'CellPhone',
           
            // 'PermanantAddress',
            // 'TemporaryAddress',
            // 'HireDate',
            // 'JoinDate',
            // 'PromotedDate',
            // 'Salary',
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
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {link}',
                    'buttons' => [
                    'link' => function ($url,$model,$key) {

                            return Html::button('', ['email'=>$model->Email, 'employeeid'=>$key, 'title'=>'Send Email', 'name'=>'emailSender','class' => 'glyphicon glyphicon-envelope']);
                        },
                    ],
                ],
            ]
    ]); ?>
</div>


<?php 
    $this->registerCss("
            .excelimport{
                padding-left:30%;
            }
        ");
 ?>

 <?php 
$js = <<< JS

$("div.excelimport").find('button[name="uploadfile"]').on("click", function(){
    var file_data = $("#ExcelFile").prop("files")[0];   
    var form_data = new FormData();                  
    form_data.append("file", file_data); 

    $.ajax({
                url: "importexcel",
                dataType: 'script',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post'
       });
});

$('div#w4').find('table button[name="emailSender"]').on('click',function(){
  console.log("in");
    var employeeid = $('div#w4').find('table button[name="emailSender"]').attr('employeeid');
    var employeeemail = $('div#w4').find('table button[name="emailSender"]').attr('email');

    $.ajax({
              type: "POST",
              url: "../../site/resetpwd",
              data:{
                "employeeid": employeeid,
                "employeeemail": employeeemail
              },
              dataType:'json',
              cache: false,
              success: function(data) {
                    if(data == 1){
                            // $("#successMsg").show();
                            // setTimeout(function() { $("#successMsg").hide(); }, 5000); 
                      showMessage("Email Sent Successfully.");
                    }
              },
              error:function(){
                 showError("Email not sent. Server Error.");
        }
    });
});

JS;

$this->registerJS($js);
  ?>