<?php

namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Advance;
use backend\modules\user\controllers\UserController;

use Date;
use DateTime;
use DatePeriod;
use DateInterval;

class AdvanceController extends \yii\web\Controller
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
	                'only' => ['index','deductions'],
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
    	$model = new Advance();
        //query for showing in the grid
        $connection = Yii::$app->getDb();

        $Advances = $connection->createCommand("SELECT A.EmployeeID, E.FullName as Name, SUM(A.Amount) as Amount FROM advance A LEFT JOIN employee E ON E.EmployeeID = A.EmployeeID where A.IsPaid = 0  GROUP BY A.EmployeeID");

        $advances = $Advances->queryAll();

        // $query = new Query();
        // $advances = $query->select(['A.EmployeeID', 'E.FullName as Name', 'SUM(A.Amount) as Amount'])->from('advance A')->leftjoin('employee E', 'E.EmployeeID = A.EmployeeID')->where(['A.IsPaid' => 0])->groupBy(['A.EmployeeID'])->all();
        //end of query part

        return $this->render('index',[
        	'model' => $model,
        	'advances' => $advances
        ]);
    }

    public function actionSavedata(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true)
        {
            $rule = $_POST["rule"];            
            $AdvanceArray = $_POST["advanceArray"];
            if($rule != null && sizeof($AdvanceArray) != 0)
            {            
            if($rule == 0){
                $model = new Advance();
                foreach ($AdvanceArray as $key => $AArray) {
                    $name = $AArray['EmployeeID'];
                    $amount = $AArray['Amount'];                   
                }
                $model->CreatedDate = date('Y-m-d');
                $model->Year = date('Y');
                $model->CreatedBy =Yii::$app->session['UserID'];
                $model->EmployeeID = $name;
                $model->Amount = $amount;
                $model->Rule = $rule;
                $model->save();                
                return '{"result":true,"message":"saved successfully"}';
            }
            else
            {
                foreach ($AdvanceArray as $key => $AArray) {
                $model = new Advance();
                    $name = $AArray['EmployeeID'];
                    $TotalAmount = $AArray['Amount'];
                    $Message = explode("-", $AArray["AdvanceOf"]);
                    $Year = $Message[0];
                    $Month = date('m', strtotime($Message[1]));
                    $amount = $Message[2];

                    $model->CreatedDate = date('Y-m-d');
                    $model->Year = $Year;
                    $model->CreatedBy =Yii::$app->session['UserID'];
                    $model->EmployeeID = $name;
                    $model->Amount = $amount;
                    $model->Month = $Month;
                    $model->Rule = $rule;
                $model->save();
                }
                return '{"result":true,"message":"saved successfully"}';
            }
            
            }
        } 
    }

    public function actionDeductions(){
        $loanMonths = $_POST["DeductionMonths"];
        $advanceTaken = $_POST["AdvanceAmount"];
        if(!empty($loanMonths) && !empty($advanceTaken)){
                $start = date("Y/m/d");
                $interval = DateInterval::createFromDateString('1 month');
                $end = date('Y/m/d', strtotime(sprintf("+%d months", $loanMonths)));
                $periods = new DatePeriod(new DateTime($start), $interval, new DateTime($end));
                $array = array();
                foreach($periods as $period){
                    $array []= date('Y-F', strtotime($period->format('Y-m')));
                }
                $response = Yii::$app->response;
                $response->format = \yii\web\Response::FORMAT_JSON;
                $response->data = $array;
            return $response;
        }
    }

}
