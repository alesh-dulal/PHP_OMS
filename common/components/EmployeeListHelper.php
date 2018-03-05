<?php
namespace common\components;

use Yii;
use yii\db\Query;
use yii\base\Component;
use backend\modules\user\models\Employee;
use backend\modules\user\models\ListItems;

class EmployeeListHelper extends Component{
		public function listEmployee(){
				$query = new Query();      
	       		$connection = Yii::$app->getDb();
	       		$rol=strtolower(Yii::$app->session['Role']);
	       		$whereCondition=" and Supervisor=".Yii::$app->session['EmployeeID'];
	       		$command = $connection->createCommand( "
	       				select EmployeeID, FullName FROM `employee` where EmployeeID NOT IN
					(
					    (SELECT MIN(EmployeeID) FROM employee)
					)
					 AND IsActive = '1'".(($rol=='supervisor')?$whereCondition:"")
	        			);
	        	$employee = $command->queryAll();
	        	$employeeList = (count($employee) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($employee, 'EmployeeID', 'FullName');

	        return $employeeList;
		}

		public function designationList(){
			$query = new Query();      
	       		$connection = Yii::$app->getDb();
	       		$command = $connection->createCommand( "
	       			select ListItemID, Title  FROM `listitems` WHERE Type= 'designation'
	       				");
	       		$designation = $command->queryAll();
	       		$designationList = (count($designation) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($designation, 'ListItemID', 'Title');
	       		return $designationList;
		}
}
?>