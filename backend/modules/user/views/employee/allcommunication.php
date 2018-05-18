<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\user\controllers\UserController;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\Employee */

$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="all-communication-view">
	<?php /*echo "<pre>"; print_r($res);*/ ?>
<table class="all-comm table table-bordered">
	<thead>
		<tr>
			<th>Date</th>
			<th>Description</th>
			<th>Communicated With</th>
			<th>Tags</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach ($data as $dta): ?>
				<tr>
				<td><?= $dta['CreatedDate']?></td>			
				<td><?= $dta['Details']?></td>			
				<td><?= $dta['TalkedWith']?></td>			
				<td><?= $dta['Tags']?></td>			
					
				</tr>
		<?php endforeach; ?>

	</tbody>
</table>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
 <?php 
$js = <<< JS

JS;
$this->registerJS($js);
?>