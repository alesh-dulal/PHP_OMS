<?php

namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Virtualpayroll;
use backend\modules\user\models\Virtualemployee;
use backend\modules\user\controllers\UserController;
class VirtualpayrollController extends \yii\web\Controller
{
    public function __construct($id, $module, $config = [])
    {
        $menus = Yii::$app->session['Menus'];
        $menusarray = (explode(",", $menus));
        parent::__construct($id, $module, $config);
        $flag = in_array("payroll", $menusarray) ? true : false;
        if ($flag == false)
        {
            $this->redirect(Yii::$app->urlManager->baseUrl . '/dashboard');
            return false;
        }
    }

    public function behaviors()
        {
            return [
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'only' => ['index','update','calculate'],
                    'rules' => [
                         // allow authenticated users
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        // everything else is denied
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
        $this->layout = '@backend/views/layouts/paymain';
        $model = Virtualemployee::find()->select('VirtualEmployeeID, FullName')->all();
        $date = date('Y-m-d');
        $newdate = strtotime('-1 month', strtotime($date));
        $sentMonth = date('F', $newdate);
        $sentYear = date('Y', $newdate);
        return $this->render('index', ['model'=>$model, 'sentYear' => $sentYear, 'sentMonth' => $sentMonth]);
    }

    public function actionCalculate()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            $employeeid = $_POST['EmployeeID'];
            $noofarticles = $_POST['TotalArticles'];

            if($employeeid != NULL && $noofarticles != NULL){                
                try {
                $connection = Yii::$app->getDb();
                $Employee = $connection->createCommand("
                SELECT `VirtualemployeeID`, `FullName`,`BankAccountNumber`,`Email`, `PerArticle` FROM `virtualemployee` WHERE `VirtualEmployeeID`= '".$employeeid."' AND `IsActive` = '1'  AND `IsTerminated` = '0'");
                $listEmployees = $Employee->queryOne();
                
                $PerArticle = $listEmployees['PerArticle'];

                $income = $PerArticle * $noofarticles;

                return '{"result":true, "income":'.$income.', "message":"Calculated."}';
                    
                } catch (Exception $e) {
                    return '{"result":false, "message":'.$e.'}';
                }

            }else{
                return '{"result":false, "message":"Data Not Available."}';
            }

        }
    }

    public function actionSave()
    {
       $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            $EmployeeID = $_POST['EmployeeID'];
            $Month = $_POST['Month'];
            $Year = $_POST['Year'];
            $TotalArticles = $_POST['TotalArticles'];
            $Income = $_POST['Income'];
            $Bonus = $_POST['Bonus'];
            $OtherTax = $_POST['OtherTax'];
            $Advance = $_POST['Advance'];
            $Remarks = $_POST['Remarks'];
            if ($EmployeeID != NULL && $Month != NULL && $Year != NULL && $Income != NULL && $Bonus != NULL && $OtherTax != NULL && $Advance != NULL) {
                $GrossIncome = $Income + $Bonus;
                $SST = (1/100)*$GrossIncome;
                $NetIncome = $GrossIncome - $OtherTax - $SST;
                $PayableAmount = $NetIncome - $Advance;

                $nameEmail = $this->getNameEmail($EmployeeID);
                $json = json_decode($nameEmail, true);
                $Name = $json['Name'];
                $Email = $json['Email'];
                $check = $this->checkPayrollIfExist($EmployeeID, $Year, $Month);
                if($check == TRUE){
                    return '{"result":false, "message":"Payroll Already Exist."}';
                }else{
                try {
                    $model = new Virtualpayroll();
                    $model->VirtualEmployeeID = $EmployeeID;
                    $model->VirtualEmployeeName = $Name;
                    $model->VirtualEmployeeEmail = $Email;
                    $model->Month = $Month;
                    $model->Year = $Year;
                    $model->TotalNoArticle = $TotalArticles;
                    $model->Income = $Income;
                    $model->Bonus = $Bonus;
                    $model->NetIncome = $NetIncome;
                    $model->SST = $SST;
                    $model->OtherTAX = $OtherTax;
                    $model->Advance = $Advance;
                    $model->Remarks = $Remarks;
                    $model->PayableAmount = $PayableAmount;
                    $model->CreatedDate = Date('Y-m-d H:i:s');
                    $model->CreatedBy = Yii::$app->session['EmployeeID'];
                    if($model->save(false)){return '{"result":true, "message":"Saved Successfully."}';}else{return '{"result":false,"message":"Not Saved."}';}
                    
                } catch (Exception $e) {
                    return '{"result": false, "message": "Caught Exception.'.$e.'"}';
                }
            }
            }else{
                return '{"result": false, "message": "Data Incomplete. Not Saved."}';
            }

        } 
    }

    public function checkPayrollIfExist($employeeid, $year, $month)
    {
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT COUNT(*) as flag FROM `virtualpayroll` WHERE `VirtualEmployeeID` = '%d' AND `Month` = '%d' AND `Year` = '%d' AND `IsActive` = '1'",$employeeid, $month, $year);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        return (($res['flag'] == 1) ? true : false);
    }


    public function getNameEmail($id)
    {
        $connection = Yii::$app->getDb();
        $Employee = $connection->createCommand("SELECT `VirtualemployeeID`, `FullName`,`BankAccountNumber`,`Email`, `PerArticle` FROM `virtualemployee` WHERE `VirtualEmployeeID`= '".$id."' AND `IsActive` = '1'  AND `IsTerminated` = '0'");
        $listEmployees = $Employee->queryOne();
        return '{"Name":"'.$listEmployees['FullName'].'", "Email":"'.$listEmployees['Email'].'"}';
    }

    public function actionGetemployeepayroll()
    {
     $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            $M = $_POST['Month'];
            $Y = $_POST['Year'];
            $date = date('Y-m-d');
            $newdate = strtotime('-1 month', strtotime($date));
            $html ="";
            if ($M == 0 && $Y == 0) {            
            $sentMonth = date('n', $newdate);
            $sentYear = date('Y', $newdate);
            $html = "";
            } else {
                $sentMonth = $M;
                $sentYear = $Y;
            }
            try {
                $payrollEmployees = virtualpayroll::find()->where(['Year' => $sentYear, 'Month' => $sentMonth, 'IsActive' => 1])->all();
                if (sizeof($payrollEmployees) <= 0) {
                    $html .= '<tr><td colspan="10" align="center">No Data Available</td></tr>';
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['tablebody' => $html,'result' => true,'year' => $sentYear,'month' => date("F", mktime(0, 0, 0, $sentMonth, 10)), "monthnum" => $sentMonth];
                } else {
                    
                foreach ($payrollEmployees as $key => $payrollEmployee)
                {
                    if($payrollEmployee['IsPaid'] == 1 && $payrollEmployee['IsProcessed'] == 1){
                    $html .= '<tr class="paid" attr-id = "'.$payrollEmployee['VirtualEmployeeID'].'">';
                        $html .= "<td>Paid</td>";  
                    }else{
                        $html .= '<tr class="processed" attr-empid = "'.$payrollEmployee['VirtualEmployeeID'].'" attr-name="'.$payrollEmployee['VirtualEmployeeName'].'">';
                        $html .= '<td><button  attr-year="'.$payrollEmployee['Year'].'" attr-month="'.$payrollEmployee['Month'].'" title="Unprocess" class="unprocess-salary btn btn-default btn-xs"><span class = "glyphicon glyphicon-remove"></span></button>      <button attr-year="'.$payrollEmployee['Year'].'" attr-month="'.$payrollEmployee['Month'].'" title="Pay" class="pay-salary btn btn-default btn-xs"><span class = "glyphicon glyphicon-ok"></span></button></td>';  
                         
                    }
                    $html .= "<td>" . $payrollEmployee['VirtualEmployeeName'] . "</td>";
                    $html .= '<td style="display: none;"> Bank Account Number </td>';
                    $html .= "<td>" . $payrollEmployee['TotalNoArticle'] . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Income']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Bonus']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['SST']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['OtherTAX']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['NetIncome']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Advance']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PayableAmount']) . "</td>";
                    $html .= "</tr>";
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['tablebody' => $html,'result' => true,'year' => $sentYear,'month' => date('F', $newdate), "monthnum" => $sentMonth];
                }
            } catch (Exception $e) {
                return '{"result": false, "message":"Data cannot be retrieved."}';
            }
        }
    }






    public function actionUnprocess(){
         $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
                $EmployeeID = $_POST['EmployeeID'];
                $Month = $_POST['Month'];
                $Year = $_POST['Year'];
            try
            {
                $query = new Query();
                $connection = Yii::$app->getDb();

                $qry1 = sprintf("UPDATE `virtualpayroll` SET IsActive = '0', `IsProcessed` = '0' WHERE `virtualpayroll`.`VirtualEmployeeID` = '%d' AND `virtualpayroll`.`Month` = '%d' AND `virtualpayroll`.`Year` = '%d' AND `virtualpayroll`.`IsActive` = '1'", $EmployeeID, $Month, $Year);

                $result1 = $connection->createCommand($qry1)/*->getRawSql()*/;
                $res1 = $result1->execute();
                
                $return = ($res1 == TRUE) ? '{"result":true,"message":"UnProcessed Successfully"}' : '{"result":false,"message":"UnProcess Failed"}';
                return $return;
            }
            catch(Exception $e)
            {
              return '{"result":false,"message":"'.$e.'"}';  
            }
        }
    }

    public function actionPaysalary(){
         $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
                $EmployeeID = $_POST['EmployeeID'];
                $Month = $_POST['Month'];
                $Year = $_POST['Year'];
            try
            {
                $query = new Query();
                $connection = Yii::$app->getDb();

                $qry1 = sprintf("UPDATE `virtualpayroll` SET `IsPaid` = '1' WHERE `virtualpayroll`.`VirtualEmployeeID` = '%d' AND `virtualpayroll`.`Month` = '%d' AND `virtualpayroll`.`Year` = '%d' AND `virtualpayroll`.`IsActive` = '1'", $EmployeeID, $Month, $Year);

                $result1 = $connection->createCommand($qry1)/*->getRawSql()*/;
                $res1 = $result1->execute();
                
                $return = ($res1 == TRUE) ? '{"result":true,"message":"Salary Paid Successfully"}' : '{"result":false,"message":"Payment Failed"}';
                return $return;
            }
            catch(Exception $e)
            {
              return '{"result":false,"message":"'.$e.'"}';  
            }
        }
    }




    
}
