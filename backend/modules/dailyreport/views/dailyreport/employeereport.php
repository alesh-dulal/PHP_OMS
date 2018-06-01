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

$this->title = "Summary";

?>
<div class="container" id="summaryContainer">
	<div class="row">
		<h3 align="center">Employee Summary</h3>
		<h5 align="center"><strong>From:- </strong><span id="attnFrom"><?php echo date('d M Y', strtotime($from)); ?></span><strong> To:-</strong> <span id="attnTo"><?php echo date('d M Y', strtotime($to)); ?></span></h5>
		<div class="well">
			<table align="center" width="99%">
			<tbody>
				<tr width = "99%">
					<td width = "33%"><h4><strong>Name: </strong><?= $Name ?></h4></td>
					<td width = "33%"><h5><strong>Total Day: </strong><?=$TotalDaysOfThisMonth?> Days</h5></td>
					<td width = "33%"><h5><strong>Total Attendance: </strong><?= $Attendance ?></h5></td>
				</tr>
				<tr width = "99%">
					<td width = "33%"><h5><strong>Total Working Hour: </strong><?= gethms($WorkingHour) ?></h5></td>
					<td width = "33%"><h5><strong>Total Task Done: </strong><?= $TotalTaskDone ?></h5></td>
					<td width = "33%"><h5><strong>Total Late Login: </strong><?= $LoginLate ?></h5></td>
				</tr>
				<tr width = "99%">
					<td width = "33%"><h5><strong>Total Exit Fast: </strong><?= $ExitFast ?></h5></td>
					<td width = "33%"><h5><strong>Total Login IP: </strong><?= $LoginIPCount ?></h5></td>
					<td width = "33%"><h5><strong>Total Exit IP: </strong><?= $ExitIPCount ?></h5></td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
<div class="" id="employeeAttendancesTable">
	<h3 align="center">Attendances</h3>
	<table id="employeeAttendances" class="employee-attendances table table-bordered">
		<thead>
			<th width="3%">#</th>
			<th>Day</th>
			<th>Login Time</th>
			<th>Login Late</th>
			<th>Exit Time</th>
			<th>Exit Fast</th>
			<th>Stay Time</th>
			<th>Report</th>
			<th>Total Submitted</th>
			<th>Status</th>
		</thead>
		<tbody>
			<?php 
			$html = "";
			$i = 1;
			if(empty($model)){
				$html .= '<tr><td align="center" colspan="10">No Data Available</td></tr>';
			}else{				
			foreach ($model as $key => $mo) {
				$html .= '<tr>';
				$html .= '<td>'.$i.'</td>';
				$html .= '<td>'.$mo['Day']."<br/>".date("l",strtotime($mo['Day'])).'</td>';
				$html .= '<td>'.date("h:i A", strtotime(getTime($mo['LoginTime']))).'</td>';
				$html .= '<td>'.$mo['LoginLate'].'</td>';
				$html .= '<td>'.date("h:i A", strtotime(getTime($mo['ExitTime']))).'</td>';
				$html .= '<td>'.$mo['ExitFast'].'</td>';
				$html .= '<td>'.gethms($mo['StayTime']).'</td>';
				$html .= '<td>'.$mo['Report'].'</td>';
				$html .= '<td>'.$mo['TotalTask'].'</td>';
				$Status = ($mo['IsAccepted'] == 1)?"Accepted":"Pending";
				$html .= '<td>'.$Status.'</td>';
				$html .='</tr>';
				$i++;
			}
			}
				echo $html;
			?>
		</tbody>
	</table>
</div>

<div class="" id="employeeIpTable">
	<h3 align="center">IP Lists</h3>
	<table id="employeeIp" class="employee-ip table table-bordered">
		<thead>
			<th width="3%">#</th>
			<th>Day</th>
			<th>Computer Name</th>
			<th>Login IP</th>
			<th>Exit IP</th>
		</thead>
		<tbody>
	<?php 
			$html = "";
	if(empty($model)){
				$html .= '<tr><td align = "center" colspan="5">No Data Available</td></tr>';
			}else{
			$i = 1;
			foreach ($model as $key => $mo) {
				$html .= '<tr>';
				$html .= '<td>'.$i.'</td>';
				$html .= '<td>'.$mo['Day']." ".date("l",strtotime($mo['Day'])).'</td>';
				$html .= '<td>'.$mo['HostName'].'</td>';
				$html .= '<td>'.$mo['LoginIP'].'</td>';
				$html .= '<td>'.$mo['ExitIP'].'</td>';
				$html .='</tr>';
				$i++;
			}
		}
				echo $html;
			?>
		</tbody>
	</table>
</div>
</div>

<?php 
    function gethms($duration) {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        return "$hours Hrs $minutes Min";
    }
     function getTime($duration) {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        $seconds = $duration % 60;
    return "$hours:$minutes:$seconds";
    }
 ?>