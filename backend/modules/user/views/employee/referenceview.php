<?php 
use yii\helpers\Html;
$this->title = $Name."_References";
$htmlRef = " ";
$i = 1;
foreach ($model as $key => $mo) {
	$htmlRef .= '<tr>';
	$htmlRef .= '<td>'.$i.'</td>';
	$htmlRef .= '<td>'.$mo['Type'].'</td>';
	$htmlRef .= '<td>'.$mo['Title'].'</td>';
	$htmlRef .= '<td>'.$mo['ReferenceNumber'].'</td>';
	$htmlRef .= '<td>'.$mo['Details'].'</td>';
	$htmlRef .= '<td>'.$mo['CreatedDate'].'</td>';
	$htmlRef .= '<td>'.Html::a('', ['download?filename='.$mo['File']], ['title'=>'Download','class' => 'hand reference-download glyphicon glyphicon-download-alt']).'</td>';
	$htmlRef .= '</tr>';
}
 ?>

 <div class="row well">
 	<div class="col-md-12">
 		<table class="table-references table table-bordered" id="tableReferences" name="tablereferences">
 			<thead>
 				<th>#</th>
 				<th>Type</th>
 				<th>Title</th>
 				<th>Reference Number</th>
 				<th>Details</th>
 				<th>Issued On</th>
 				<th>File</th>
 			</thead>
 			<tbody>
 				<?= $htmlRef ?>
 			</tbody>
 		</table>
 	</div>
 </div>