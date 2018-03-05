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

    public function actionCalculations()
    {
    	$Role = UserController::CheckRole("payroll");
        if($Role == true){
	    	$employeeID = $_POST['employeeID'];
	    	$query = new Query();
	    	$calc = $query->select(['DS.SalaryAmount as BasicSalary'])->from('employee E')->leftjoin('designationsalary DS', 'DS.DesignationID = E.DesignationID')->where(['E.IsActive'=>1,'EmployeeID'=>$employeeID])->one();
	    		$response = Yii::$app->response;
	            $response->format = \yii\web\Response::FORMAT_JSON;
	            $response->data = $calc;
	    	return $response;
        }
    }

    public function actionAllowancelist(){
    	$Role = UserController::CheckRole("payroll");
        if($Role == true){
		$query = new Query();
		$allowances = $query->select(['IsAllowance','Title','Amount','Formula'])->from('payrollsetting')->where(['IsActive'=>1])->all();

		if($allowances != NULL && sizeof($allowances) > 0){
			$val = NULL;
			foreach($allowances as $allowance):
				if (strtolower($allowance['Title']) ==  'gratuity') {
					 echo $allowance['Formula']; 
				}
			endforeach;
			// $response = Yii::$app->response;
   //      	$response->format = \yii\web\Response::FORMAT_JSON;
   //      	$response->data = $allowances;
    		//return $response;
    		return $val;
			}
        }

    }
}
