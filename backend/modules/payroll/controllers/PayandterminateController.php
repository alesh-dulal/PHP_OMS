<?php

namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Payroll;
use backend\modules\user\controllers\UserController;
use backend\modules\payroll\controllers\PayrollController;

class PayandterminateController extends \yii\web\Controller
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
            $model = new \backend\modules\payroll\models\Payroll();

            if ($model->load(Yii::$app->request->post())) {       
                
                        $model->CreatedDate = Date("Y-m-d");
                        $model->CreatedBy = Yii::$app->session['EmployeeID'];
                        $model->FullName = $this->getEmployeeName($model->EmployeeID);
                        $model->IsProcessed = "1";
                        $model->IsPaid = "1";
                        if($model->save()){
                          $log = $this->keepLog($model->EmployeeID, $model->Month, $model->Year);
                          if ($log == TRUE) {
                              $Terminate = $this->terminateEmployee($model->EmployeeID);
                          }
                            if ($Terminate == TRUE) {
                               Yii::$app->session->setFlash('terminated', " ".$this->getEmployeeName($model->EmployeeID)." has been terminated successfully."); 
                            }
                        }

            }
            return $this->render('index', [
                'model' => $model,
            ]);
        }

        public function actionEmpsalary(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            try {
                $employeeID = $_POST['employeeID'];

                if ($employeeID != NULL && $employeeID != 0) {
                    $data = $this->actionEmployeeabsentdays($employeeID);

                    if($data['result'] == FALSE){return '{"result":"'.$data['result'].'","message":"'.$data['message'].'"}';}else{

                    $WorkingDays = $data['WorkingDays'];

                    $TotalDays = $this->TotalDaysOfMonth();

                    $query = new Query();
                    $calc = $query->select(['Salary as BasicSalary'])->from('employee E')->where(['E.IsActive'=>1,'EmployeeID'=>$employeeID])->one();

                    $basicSalary = $calc['BasicSalary'];

                    $onedaySalary = $basicSalary/$TotalDays;
                    $SalaryTillDate = $onedaySalary * $WorkingDays;

                    return '{"result":true,"bs":'.number_format((float)$SalaryTillDate, 0, '.', '').',"message":"Salary Calculated."}'; }

                } else {
                   if ($employeeID == 0){
                       return '{"result":"zero","message":"BasicSalary UnLoaded."}'; 
                       }  
                       if ($employeeID == NULL){
                       return '{"result":false,"message":"Employee Not Selected."}'; 
                       }                  
                   }
            } catch (Exception $e) {
                return $e;    
            }
        }
    }

    public function actionGetfields(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            try {
               $query = new Query();
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("SELECT PayrollSettingID, IsAllowance, Title FROM `payrollsetting` WHERE `IsActive` = 1");
                $Rows = $command->queryAll();
                $htmlA = "";
                $htmlD = "";
            foreach($Rows as $fields){
                if($fields['IsAllowance'] == 0){

                    $htmlA .= '<div class="form-group col-lg-3">';
                    $htmlA .= '<label for="'.str_replace(" ","",$fields["Title"]).'">'.$fields["Title"].'</label>';
                    $htmlA .= '<input value="0" class="form-control calculate" type="text" id="'.str_replace(" ","",$fields["Title"]).'" name="Payroll['.str_replace(" ","",$fields["Title"]).']">';
                    $htmlA .= '</div>';

                }else{
                    $htmlD .= '<div class="form-group col-lg-3">';
                    $htmlD .= '<label for="'.str_replace(" ","",$fields["Title"]).'">'.$fields["Title"].'</label>';
                    $htmlD .= '<input value="0" class="form-control calculate" type="text" id="'.str_replace(" ","",$fields["Title"]).'" name="Payroll['.str_replace(" ","",$fields["Title"]).']">';
                    $htmlD .= '</div>';
                }
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['htmlAllowance' => $htmlA,'htmlDeduction' => $htmlD];
            }
            catch(Exception $e){
                return $e;
            }
        }
    }

    public function actionEmployeeabsentdays($id = NULL)
    {
        if($id == NULL){$id = $_POST['ID'];}
        $WorkedHours = 0;
        $WorkingHours = 0;
        $AbsentDays = 0;
        $workedTimeTotal = 0;
        $WorkedDays = 0;
        $OTHours = 0;
        $InsuffHours = 0; $InsuffHourSalary = 0; $InsuffHourSalaryDeduction = 0; $AbsentHours = 0; $TotalWorkingHourAfter = 0; $InsuffHours = 0; $InsuffHourSalary = 0; $InsuffHourSalaryDeduction = 0;

        $firstDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-0 month"));
        $lastDateOfPrevMonth = date("Y-m-d"/*, strtotime(date("Y-m-d", strtotime(date("Y-m-t"))) . "-0 month")*/);
        $TD = date_diff(date_create($firstDateOfPrevMonth) , date_create($lastDateOfPrevMonth));
        $TotalDays = intval($TD->format("%a"));

        $Saturdays = PayrollController::getSaturdays($firstDateOfPrevMonth, $lastDateOfPrevMonth);

    $connection = Yii::$app->getDb();
    $Employee = $connection->createCommand("
    SELECT `EmployeeID`, `FullName`,`BankAccountNumber`,`Email`, `Salary` from `employee` WHERE EmployeeID NOT IN (SELECT MIN(EmployeeID) FROM employee) AND IsActive = 1 AND EmployeeID=".$id."");
    $listEmployees = $Employee->queryAll();

    $queryAttendance = sprintf("SELECT DISTINCT(AttnDate), EmployeeID, WorkedTime from attendance WHERE `AttnDate` BETWEEN '%s' and '%s' and IsActive = 1", $firstDateOfPrevMonth, $lastDateOfPrevMonth);
    $Attendance = $connection->createCommand($queryAttendance);
    $listAttendance = $Attendance->queryAll();

    $Holiday = $connection->createCommand("SELECT COUNT(*) as Days FROM holiday WHERE MONTH(Day) = MONTH(DATE_ADD(Now(), INTERVAL -0 MONTH))");
    $Holidays = $Holiday->queryOne();

    $Setting = $connection->createCommand("SELECT `SalaryAmendmentID`,`TotalWorkingHourPerDay`,`MaximumOTHoursPerDay`,`MaximumPaidLeaveDays`,`SalaryCalcPercentOfOTHours`,`SalaryDeductionOfLessHours` FROM `salarycalculationamendment` WHERE `IsActive` = 1");
    $listSetting = $Setting->queryAll();

    if(sizeof($listSetting)<=0){Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // return '{"result":false, "message":""}';
        return [
            'result'=>false,
            'message' => 'Salary Calculation Settings Not Available.'
        ];
    }

    $AttendanceSetting = $connection->createCommand("SELECT * FROM `payrollattendance` WHERE `IsActive` = 1");
    $listAttendanceSetting = $AttendanceSetting->queryAll();

        foreach ($listSetting as $key => $LS) {
            $TotalWorkingHourPerDay = $LS["TotalWorkingHourPerDay"];
            $MaximumOTHoursPerDay = $LS["MaximumOTHoursPerDay"];
            $MaximumPaidLeaveDays = $LS["MaximumPaidLeaveDays"];
            $SalaryCalcPercentOfOTHours = $LS["SalaryCalcPercentOfOTHours"];
            $SalaryDeductionOfLessHours = $LS["SalaryDeductionOfLessHours"];
        }

        $data = array_filter($listAttendance, function ($emp) use ($id)
        {
            return $emp['EmployeeID'] == $id;
        });

        $payrollAttnData = array_filter($listAttendanceSetting, function ($emp) use ($id)
        {
            return $emp['EmployeeID'] == $id;
        });

        if($payrollAttnData != null){

            foreach($payrollAttnData as $AttnData){$WorkedDays = $AttnData['AttendanceDays'];}

        }else{
            foreach($data as $attnData)
            {
                $workedTimeTotal += PayrollController::timeToSec($attnData['WorkedTime']);
                $WorkedDays++;
            }
        }

        $BasSal = PayrollController::getEmployeeBS($id); //Basic Salary Of the employee

        $WorkingDays = $TotalDays - $Saturdays - $Holidays['Days'];

        $WorkingHours = $WorkingDays * $TotalWorkingHourPerDay;
        $OneHourSalary = $BasSal  / $WorkingHours;

        $TotalAbsentDays = ($WorkedDays > $WorkingDays)? 0: $WorkingDays - $WorkedDays;

        $AbsentDaysWithDeduction = (($TotalAbsentDays) > $MaximumPaidLeaveDays) ? $TotalAbsentDays - $MaximumPaidLeaveDays : 0;

        $AbsentHours = ($AbsentDaysWithDeduction * $TotalWorkingHourPerDay);
        $AbsentDeduction = number_format((float)$AbsentHours * ($OneHourSalary*1.5), 2, '.', '');
        $WorkedTimeSecs = PayrollController::timeToSec(PayrollController::getTime($workedTimeTotal));
        $WorkingTimeSecs = PayrollController::timeToSec($WorkingHours . ":00:00");
        $OTHours = $WorkedTimeSecs - $WorkingTimeSecs;
        $OT = ($OTHours > $WorkingHours) ? PayrollController::getTime($OTHours) : 0;

        $OTHourSalary = ($OneHourSalary * 1.5) * PayrollController::decimalHours($OT);
        $OTBonus = number_format((float)$OTHourSalary, 2, '.', '');
        
        if ($AbsentDays - $MaximumPaidLeaveDays  == 0)
        {
            if (PayrollController::timeToSec($WorkingHours . ":00:00") > $workedTimeTotal)
            {
                $InsuffHours = PayrollController::getTime(PayrollController::timeToSec($WorkingHours . ":00:00") - $workedTimeTotal);
                $InsuffHourSalary = ($OneHourSalary * 1.5) * PayrollController::decimalHours($InsuffHours);
                $InsuffHourSalaryDeduction = number_format((float)$InsuffHourSalary, 2, '.', '');
            }
        }

        if ($AbsentDays- $MaximumPaidLeaveDays > 0)
        {
            if (PayrollController::timeToSec($WorkingHours . ":00:00") > $workedTimeTotal)
            {
                $AbsentHours = PayrollController::timeToSec($MaximumPaidLeaveDays*$TotalWorkingHourPerDay.":00:00");
                $TotalWorkingHourAfter = PayrollController::timeToSec($WorkingHours . ":00:00")-$AbsentHours;
                $InsuffHours = PayrollController::getTime( $TotalWorkingHourAfter - $workedTimeTotal);
                $InsuffHourSalary = ($OneHourSalary * 1.5) * PayrollController::decimalHours($InsuffHours);
                $InsuffHourSalaryDeduction = number_format((float)$InsuffHourSalary, 2, '.', '');
            }
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'result' => true,
            'AbsDays' => $TotalAbsentDays, 
            'AbsDeduct' => $AbsentDeduction,
            'OTHours' => $OT,
            'OTBonus' => $OTBonus,
            'InsufficientHours' => $InsuffHours,
            'InsufficientHoursSalaryDeduction' => $InsuffHourSalaryDeduction,
            'WorkedDays' => $WorkedDays,
            'WorkingDays' => $WorkingDays
        ];

    }

function TotalDaysOfMonth(){
        $firstDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-0 month"));
        $lastDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-t"))) . "-0 month"));
        $TD = date_diff(date_create($firstDateOfPrevMonth) , date_create($lastDateOfPrevMonth));
        $TotalDays = intval($TD->format("%a"));
        return $TotalDays;
    }



    public function actionEmployeadvance()
    {

        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            $id = $_POST['ID'];
        $date = date('Y-m-d');+
        $TotalAdvance = 0;
        $connection = Yii::$app->getDb();
        $Advance = $connection->createCommand("SELECT * FROM `advance` where IsPaid = 0 and IsActive = 1");
        $listAdvance = $Advance->queryAll();
        $data = array_filter($listAdvance, function ($adv) use ($id)
        {
            return $adv['EmployeeID'] == $id;
        });
        if (sizeof($data) == 0)
        {
            return "0";
        }
        else
        {
            foreach ($data as $la)
            {
                $TotalAdvance += $la['Amount'];
            }
        }

        return $TotalAdvance;
    }
}

    public function getEmployeeName($id)
    {
        $connection = Yii::$app->getDb();
        $Employee = $connection->createCommand("
        SELECT `FullName` from `employee` WHERE EmployeeID = ".$id);
        $listEmployees = $Employee->queryOne();
        return $listEmployees['FullName'];
    }

    public function keepLog($id, $month, $year){
        $loggedInUserID = Yii::$app->session['UserID'];
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("INSERT INTO `payrolllog` (`EmployeeID`, `Year`, `Month`,`CreatedBy`) VALUES ('%d', '%d', '%d','%d');",$id, $year, $month,$loggedInUserID);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->execute();
        $return =  (($res == 1) ? true : false);
        return $return;
    }

    public function terminateEmployee($id)
    {
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("UPDATE `employee` SET `IsTerminated` = '1' , `IsActive` = '0' WHERE `employee`.`EmployeeID` = %d",$id);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->execute();
        $return =  (($res == 1) ? true : false);
        return $return;
    }

}
