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
        $query = new Query();
        $advances = $query->select(['AdvanceID', 'E.EmployeeID', 'E.FullName as Name','Amount','Rule', 'Month','(CASE 
        	when A.Rule = 0 then "Deduct Once"
        	else "Deduct Monthly"
            END) as Rules'])->from('advance A')->leftjoin('employee E', 'E.EmployeeID = A.EmployeeID')->all();
        //end of query part
        return $this->render('index',[
        	'model' => $model,
        	'advances' => $advances,
        ]);
    }

    public function actionSavedata(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            $IsNewRecord = $_POST['isNewRecord'];
            if($IsNewRecord == 0){
                $model = new Advance();
                $model->CreatedDate = date('Y-m-d');
                $model->CreatedBy =Yii::$app->session['UserID'];               
            }else{
                $model = Advance::findOne($IsNewRecord);
                $model->UpdatedDate = date('Y-m-d');
                $model->UpdatedBy =Yii::$app->session['UserID'];
            }

            $name = $_POST["name"];
            $amount = $_POST["amount"];
            $rule = $_POST["rule"];
            $month = $_POST["month"];

            $model->EmployeeID = $name;
            $model->Amount = $amount;
            $model->Rule = $rule;
            $model->Month = $month;
            $model->save();
            return '{"result":true,"message":"saved successfully"}';
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
