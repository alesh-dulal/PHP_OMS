<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;
use kartik\daterange\DateRangePicker;
use backend\modules\user\models\Role;
use backend\modules\user\models\Employee;
use backend\modules\dailyreport\models\Dailyreport;
use yii\widgets\LinkPager;
use yii\widgets\DetailView;
$this->title = "Reporting";
?>
<div class="container" id="reportingContainer">
	<h3 align="center">Reporting</h3>
	<div class="row" id="search">
			<?php $form = ActiveForm::begin(); ?>
			<div class="col-md-12">
				<div class="col-md-4">
					<?php 
         $LoggedInEmployeeRole = Yii::$app->session['Role'];

        if(strtolower($LoggedInEmployeeRole) == "employee"||strtolower($LoggedInEmployeeRole) == "trainee"):
          echo '<h3 id="currentLoggedIn" data-employeeID='.Yii::$app->session['EmployeeID'].'>'.Yii::$app->session['FullName'].'</h3>';
        else:
            echo $form->field($model, 'UserID')->widget(Select2::classname(), [
                       'data' => Yii::$app->empList->listEmployee(),
                       'language' => 'en',
                       'options' => ['placeholder' => 'Select Employee  ...'],
                       'pluginOptions' => [
                               'allowClear' => true
                            ],
               ])->label("Employee Name");
        endif;
       ?>
				</div>
				<div class="col-md-4">
					<?php 
						echo '<label class="control-label">Report Range</label>';
						echo '<div class="drp-container">';
						echo DateRangePicker::widget([
								'name'=>'daterange',
								'presetDropdown'=>true,
								'hideInput'=>true,
								'pluginOptions'=>[
						        'locale'=>[
						            'separator'=>' to ',
						        	],
						        'opens'=>'left'
							    ]
							]);
						echo '</div>';
					 ?>
				</div>
				<div class="col-md-4">
					<?php echo Html::button(' Find', ['data-employeeID'=>Yii::$app->session['EmployeeID'], 'class' => 'btn btn-primary report-go glyphicon glyphicon-search', 'data-id'=>'findo']); ?>

				</div>
			</div>
		    <?php ActiveForm::end(); ?>
	</div>

	<div class="row">
		<div class="all-report-view">
<table class="all-report table table-bordered" id="allReport">
	 <caption>Report Of This Month</caption>
	<thead>
		<tr>
			<th>Full Name</th>
			<th>Worked Days</th>
			<th>Worked Hours</th>
			<th>Total Task Done</th>
			<th>Target Task</th>
		</tr>
	</thead>
	<tbody>
		<?= $td ?>
	</thead>

	</tbody>
</table>
</div>
	</div>
</div>

<?php 
$js = <<< JS
var ele = $('div#reportingContainer');
	ele.find('button.report-go').on('click', function(){
		var EmployeeID = ele.find('select[name="Dailyreport[UserID]"]').val();
		var Range = ele.find('input[name="daterange"]').val();
		if(EmployeeID == "" || Range == ""){
			showError("Select Data.");
		}else{
			GetReport(EmployeeID, Range);
		}
	});

	function GetReport(EmployeeID, DateRange){
		$.ajax({
        type: "POST",
        url: "getsinglereport",
        data: {
            "EmployeeID": EmployeeID,
            "Range": DateRange
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
			if(data.result == 'TRUE'){
				$('div#reportingContainer').find('table#allReport tbody').empty();
				$('div#reportingContainer').find('table#allReport tbody').append(data.html);
				showMessage(data.message);
			}else{
				showError(data.message);
			}
        },
        error: function() {
         
        }
       });
	}
JS;
$this->registerJS($js);
?>

<?php $this->registerCSS("
   #allReport tbody {
		display:block;
		height:300px;
		overflow:auto;
	}

	#allReport thead,#allReport tbody tr {
		display:table;
		width:100%;
		table-layout:fixed;
	}

	#allReport thead {
		background:#e6e6e6;
		width: calc(100%)
	}
  ");?>