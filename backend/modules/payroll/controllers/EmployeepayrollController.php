<?php

namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Employeepayroll;
use backend\modules\user\controllers\UserController;

class EmployeepayrollController extends \yii\web\Controller
{
	public function __construct($id, $module, $config = [])
	 {
	     $menus=Yii::$app->session['Menus'];
	     $menusarray=(explode(",",$menus)); 
	     parent::__construct($id, $module, $config);
	     $flag= in_array( "payroll" ,$menusarray )?TRUE:FALSE;
	    if($flag==FALSE)
	    {
	        $this->redirect(Yii::$app->urlManager->baseUrl.'/dashboard');
	         return false;
	    }
	 }
	public function behaviors()
	   {
	        return [
	            'access' => [
	                'class' => \yii\filters\AccessControl::className(),
	                'only' => ['index'],
	                'rules' => [
	                    [
	                        'allow' => true,
	                        'roles' => ['@'],
	                    ],
	                ],
	            ],
	            'verbs' => [
	                'class' => VerbFilter::className(),
	                'actions' => [
	                    'delete' => ['POST'],
	                ],
	            ],
	        ];
	    }
    public function actionIndex()
    {
    	$model = new Employeepayroll();
        return $this->render('index',[
        	'model'=>$model,
        ]);
    }    

	public function actionEmpsalary()
    {
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            try {
	            $employeeID = $_POST['employeeID'];
	            $query = new Query();
	            $calc = $query->select(['Salary as BasicSalary'])->from('employee E')->where(['E.IsActive'=>1,'EmployeeID'=>$employeeID])->one();
	            if ($calc !=NULL) {
		            $response = Yii::$app->response;
		            $response->format = \yii\web\Response::FORMAT_JSON;
		            $response->data = $calc;
		            return $response;
	            }
                
            } catch (Exception $e) {
            	return $e;    
            }
        }
    } 

    public function actionAllowancelist(){
    	$Role = UserController::CheckRole("payroll");
    	 if($Role == true){
    	 	try {
    	 		$query = new Query();
		        $allowances = $query->select(['PayrollSettingID', 'IsAllowance','Title','Amount','Formula'])->from('payrollsetting')->where(['IsActive'=>1])->all();
		        $allow=NULL;
		        $dedu=NULL;
		        if($allowances != NULL && sizeof($allowances) > 0){
		        	foreach ($allowances as $allowance){
		        		if($allowance['IsAllowance'] == 0){
		        			$allow .='<tr data-type="'.$allowance["IsAllowance"].'"data-id='.$allowance["PayrollSettingID"] .'>';
					         	$allow.='<td>'.$allowance['Title'].'</td>';
					         	if ($allowance['Formula'] != NULL) {
					         		$allow.='<td>'.$allowance['Formula'].'</td>';
					         	}else{
					         		$allow.='<td class="editable" contenteditable ="true">'.$allowance["Amount"].'</td>';
					         	}
					         	$allow.='<td hidden="true">'.$allowance['Formula'].'</td>';
					         	$allow .='</tr>';
					         }else{
					         	$dedu .='<tr data-type="'.$allowance["IsAllowance"].'"data-id='.$allowance["PayrollSettingID"] .'>';
					         	$dedu.='<td>'.$allowance['Title'].'</td>';
					         	if ($allowance['Formula'] != NULL) {
					         		$dedu.='<td>'.$allowance['Formula'].'</td>';
					         	}else{
					         		$dedu.='<td class="editable" contenteditable ="true">'.$allowance["Amount"].'</td>';
					         	}
					         	$dedu.='<td hidden="true">'.$allowance['Formula'].'</td>';
					         	$dedu .='</tr>';
					         }
		        		}
		        }

    	 	} catch (Exception $e) {
    	 		return $e;
    	 	}
    	 	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	        return [
	            'allowance' => $allow ,
	            'deduction' => $dedu
	        ];
    	 }
		       	 	
    }//function ends here

    public function actionCalculate(){
    	$Role = UserController::CheckRole("payroll");
    	 if($Role == true){
    	 	try {
    	 		$EmployeeID = $_POST['employeeID'];
    	 		$query = new Query();
    	 		$AllowanceID = $_POST['AllowanceID'];
    	 		if ($EmployeeID != NULL && $AllowanceID != NULL) {
    	 			$i=0; $insert ='';
					$loggedInUserID=Yii::$app->session['UserID'];

    	 			foreach ($AllowanceID as $key => $Allowance) {
    	 				if($i != 0)
 						$insert .=',';
    	 				$insert .='(';
		    	 				$insert .=$EmployeeID.",";
		    	 				$insert .=$Allowance['ID'].",";
		    	 				$insert .=$Allowance['Type'].",";
		    	 				$insert .="'".$Allowance['Name']."',";
		    	 				$insert .=$Allowance['Value'].",";
		    	 				$insert .="'".Date('Y-m-d')."',";
		    	 				$insert .=$loggedInUserID;
    	 				$insert  .= ')';
						$i++;
	    	 		}
    	 		}

        $query = new Query();
        $connection = Yii::$app->getDb();
	       $qry= sprintf("INSERT INTO `employeepayroll` (`EmployeeID`, `AllowanceID`, `IsAllowance`, `AllowanceTitle`, `AllowanceAmount`, `CreatedDate`, `CreatedBy`) VALUES %s;",$insert);
	       $result=$connection->createCommand($qry)/*->getRawSql()*/;
	       $res=$result->execute();

	       $return = $res == TRUE ? '{"result":true,"message":"Saved successfully"}':'{"result":false,"message":"Not Saved successfully"}';
	       return $return;

    	 	} catch (Exception $e) {
    	 		return $e;
    	 	}
    	} 
    }
}
