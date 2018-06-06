<?php
namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Payroll;
use backend\modules\payroll\models\Payrollsetting;
use backend\modules\user\controllers\UserController;
class PayrollController extends \yii\web\Controller
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
                'only' => ['index'],
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
        $date = date('Y-m-d');
        $newdate = strtotime('-1 month', strtotime($date));
        $sentMonth = date('m', $newdate);
        $sentYear = date('Y', $newdate);
        $query = new Query();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT PayrollSettingID, IsAllowance, Title FROM `payrollsetting` WHERE `IsActive` = 1
");
        $Rows = $command->queryAll();
        return $this->render('index', ['Rows' => $Rows, 'sentMonth' => $sentMonth, ]);
    }
    public function actionCalcpayroll()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            $date = date('Y-m-d');
            $newdate = strtotime('-1 month', strtotime($date));
            $firstDateOfPrevMonth = date('Y-m-d', strtotime('-1 day', strtotime('first day of last month')));
            $lastDateOfPrevMonth = date('Y-m-d', strtotime('last day of last month'));
            $sentYear = date('Y', $newdate);
            $sentMonth = date('m', $newdate);
            if ($this->checkForMonthPayrollIfExist($sentYear, $sentMonth) == true)
            {
                $data = $this->actionGetpayroll($sentYear, $sentMonth);
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['oldhtml' => $data, 'message' => 'Payroll of '.date("F", mktime(0, 0, 0, $sentMonth, 10)) . ' Already Exist.'];
            }
            else
            {
                try
                {
                    $connection = Yii::$app->getDb();
                    $Employee = $connection->createCommand("SELECT `EmployeeID`, `FullName`,`BankAccountNumber`,`Email`, `Salary` from `employee` WHERE EmployeeID NOT IN (SELECT MIN(EmployeeID) FROM employee) AND IsActive = 1  AND IsTerminated = 0");
                    global $listEmployees;
                    $listEmployees = $Employee->queryAll();
                    $EmployeePayroll = $connection->createCommand("SELECT `EmployeeID`, `IsAllowance`, `AllowanceTitle`, `AllowanceAmount` FROM `employeepayroll` WHERE IsActive = 1");
                    global $listEmpAllowances;
                    $listEmpAllowances = $EmployeePayroll->queryAll();
                    $Allowances = $connection->createCommand("SELECT PayrollSettingID, IsAllowance, Title as AllowanceTitle  FROM `payrollsetting` WHERE `IsActive` = 1 ");
                    global $listAllowances;
                    $listAllowances = $Allowances->queryAll();
                    $queryAttendance = sprintf("SELECT DISTINCT(AttnDate), EmployeeID, WorkedTime from attendance WHERE IsActive = 1 AND `AttnDate` BETWEEN '%s' and '%s' and ISActive = 1", $firstDateOfPrevMonth, $lastDateOfPrevMonth);
                    $Attendance = $connection->createCommand($queryAttendance);
                    global $listAttendance;
                    $listAttendance = $Attendance->queryAll();
                    $Holiday = $connection->createCommand("SELECT COUNT(*) as Days FROM holiday WHERE MONTH(Day) = MONTH(DATE_ADD(Now(), INTERVAL -1 MONTH))");
                    global $Holidays;
                    $Holidays = $Holiday->queryOne();
                    $Advance = $connection->createCommand("SELECT * FROM `advance` where IsPaid = 0 and IsActive = 1");
                    global $listAdvance;
                    $listAdvance = $Advance->queryAll();
                    $Setting = $connection->createCommand("SELECT `SalaryAmendmentID`,`TotalWorkingHourPerDay`,`MaximumOTHoursPerDay`,`MaximumPaidLeaveDays`,`SalaryCalcPercentOfOTHours`,`SalaryDeductionOfLessHours` FROM `salarycalculationamendment` WHERE `IsActive` = 1");
                    global $listSetting;
                    $listSetting = $Setting->queryAll();
                    if(sizeof($listSetting)<=0){return '{"result":false, "message":"Salary Calculation Settings Not Available."}';}
                    $attendanceSettingQry = sprintf("SELECT * FROM `payrollattendance` WHERE `Year`='%d' AND `Month`='%d' AND `IsActive` = 1", $sentYear, $sentMonth);
                    $AttendanceSetting = $connection->createCommand($attendanceSettingQry);
                    global $listAttendanceSetting;
                    $listAttendanceSetting = $AttendanceSetting->queryAll();
                    $html = "";
                    foreach ($this->getEmployeeIds() as $id)
                    {
                        $getLog = $this->getPayrollLogIfExist($id, $sentYear, $sentMonth);
                        if($getLog === false){//if exist data then $getLog will be false
                            $html .= $this->getEmppayroll($id, $sentYear, $sentMonth);
                            continue;
                        }                         
                        $checkDenial = $this->checkEmployeeIfDenied($id);
                        if($checkDenial === true){//if employee is denied for payroll
                            continue;
                        }
                        $listing[$id] = $this->getEmployeeAllowances($id);
                        $html .= '<tr attr-empid="' . $id . '">';
                        $BasicSalary = $this->getEmployeeBS($id);
                        $html .= "<td><input id='checkBox' class='payroll-check' type='checkbox'></td>";
                        $html .= "<td>" . $this->getEmployeeName($id) . "</td>";
                        $html .= "<td style='display: none;'>".$this->getEmployeeBankAccount($id)."</td>";
                        $html .= "<td style='display: none;'>".$id."</td>";
                        $html .= "<td style='display: none;'>".$this->getEmployeeEmail($id)."</td>";
                        $html .= "<td>" . number_format($BasicSalary) . "</td>";
                        $AllowanceSum = 0;
                        $DeductionSum = 0;
                        $arrayAllowances = array();
                        $arrayDeductions = array();
                        $income = 0;
                        $ADeduction = 0;
                        $GrossIncome = 0;
                        $SST = 0.00;
                        $OtherTAX = 0.00;
                        $Advance = 0.00;
                        $PayableAmount = 0.00;
                        foreach ($listing[$id] as $key => $list)
                        {
                            if ($list['IsAllowance'] == 0)
                            {
                                array_push($arrayAllowances, $list);
                            }
                            if ($list['IsAllowance'] == 1)
                            {
                                array_push($arrayDeductions, $list);
                            }
                        }
                        foreach ($arrayAllowances as $listAllow)
                        {
                            $html .= "<td>" . number_format($listAllow['AllowanceAmount']) . "</td>";
                            $AllowanceSum += $listAllow['AllowanceAmount'];
                        }
                        $html .= "<td>" . number_format($AllowanceSum). "</td>";
                        foreach ($arrayDeductions as $listDeduc)
                        {
                            $html .= "<td>" . number_format($listDeduc['AllowanceAmount']) . "</td>";
                            $DeductionSum += $listDeduc['AllowanceAmount'];
                        }
                        $html .= "<td>" . number_format($DeductionSum) . "</td>";
                        $income = $BasicSalary + $AllowanceSum - $DeductionSum;
                        $html .= "<td>" . number_format($income) . "</td>";
                        $AbsentValues = $this->getEmployeeAbsentDays($id);
                        $ADeduction = $AbsentValues["AbsDeduct"]; //Absent Deduction
                        $html .= "<td>" . number_format($AbsentValues["AbsDays"]) . "</td>";
                        $html .= "<td>" . number_format($ADeduction) . "</td>";
                        $GrossIncome = $income - $ADeduction;
                        $html .= "<td>" . number_format($GrossIncome) . "</td>";
                        $SST = (1 / 100) * $GrossIncome;
                        $html .= "<td>" . number_format($SST) . "</td>";
                        $OtherTAX = 0.00;
                        $html .= "<td>000</td>";
                        $NetIncome = $GrossIncome - ($SST + $OtherTAX);
                        $html .= "<td>" . number_format($NetIncome) . "</td>";
                        $Advance = $this->getEmployeAdvance($id);
                        $html .= "<td>" . number_format($Advance) . "</td>";
                        $PayableAmount = $NetIncome - $Advance;
                        $html .= "<td>" . number_format($PayableAmount) . "</td>";
                        $html .= "<td></td>";
                        $html .= "</tr>";
                    }
                }
                catch(Exception $e)
                {
                    return $e = null ? "server Error" : $e;
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['year' => $sentYear, 'month' => $sentMonth, 'html' => $html ,'message' => 'Calculation Done.'];
            }
        }
    }
    public function actionGetpayroll($year = NULL, $month = NULL)
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                if ($year == NULL && $month == NULL) {
                    $yearTo = $_POST['year'];
                    $monthTo = $_POST['month'];
                } else {
                    $yearTo = $year;
                    $monthTo = $month;
                }
                $payrollEmployees = Payroll::find()->where(['Year' => $yearTo, 'Month' => $monthTo, 'IsActive' => 1])->all();
                $html = "";
                foreach ($payrollEmployees as $key => $payrollEmployee)
                {
                    $html .= '<tr attr-id = '.$payrollEmployee['EmployeeID'].'>';
                    if($payrollEmployee['IsPaid'] == 1 && $payrollEmployee['IsProcessed'] == 1 ){
                        $html .= "<td>Paid</td>";  
                    }else{
                        $html .= "<td>Processed</td>"; 
                    }
                    $html .= "<td>" . $payrollEmployee['FullName'] . "</td>";
                    $html .= "<td style='display: none;'> Bank Account Number </td>";
                    $html .= "<td style='display: none;'>".$payrollEmployee['EmployeeID']."</td>";
                    $html .= "<td style='display: none;'>Email</td>";
                    $html .= "<td>" . number_format($payrollEmployee['BasicSalary']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PF']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Gratuity']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Allowance']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Grade']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Incentive']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Bonus']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['TotalAllowance']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PFDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['CITDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['TotalDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Income']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['AbsentDays']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['AbsentDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['GrossIncome']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['SST']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['OtherTAX']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['NetIncome']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['AdvanceDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PayableAmount']) . "</td>";
                    $html .= "<td>" . $payrollEmployee['Remarks'] . "</td>";
                    $html .= "</tr>";
                }
            }
            catch(Exception $e)
            {
                return $e = null ? "server Error" : $e;
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['year' => $yearTo, 'month' => $monthTo, 'tableBody' => $html];
    }
    public function getEmployeeAllowances($id)
    {
        global $listAllowances;
        $infoArray = $this->searchForEmployeeAllowances($id);
        $out = [];
        foreach ($listAllowances as $listAllowance)
        {
            $employeeContainsAllowanceTitle = false;
            foreach ($infoArray as $info)
            {
                if (strtolower($listAllowance['AllowanceTitle']) == strtolower($info['AllowanceTitle']))
                {
                    $out[] = $info;
                    $employeeContainsAllowanceTitle = true;
                }
            }
            if (!$employeeContainsAllowanceTitle)
            {
                $out[] = ["EmployeeID" => $id, "AllowanceTitle" => $listAllowance['AllowanceTitle'], "IsAllowance" => $listAllowance["IsAllowance"], "AllowanceAmount" => 0];
            }
        }
        return $out;
    }
    public function getEmployeeIds()
    {
        global $listEmployees;
        return array_map(function ($emp)
                         {
                             return $emp['EmployeeID'];
                         }
                         , $listEmployees);
    }
    public function searchForEmployeeAllowances($id)
    {
        global $listEmpAllowances;
        return array_filter($listEmpAllowances, function ($emp) use ($id)
                            {
                                return $emp['EmployeeID'] == $id;
                            });
    }
    public function getEmployeeName($id)
    {
        global $listEmployees;
        $data = array_filter($listEmployees, function ($emp) use ($id)
                             {
                                 return $emp['EmployeeID'] == $id;
                             });
        foreach ($data as $dat)
        {
            return $dat['FullName'];
        }
    }
    public function getEmployeeEmail($id)
    {
        global $listEmployees;
        $data = array_filter($listEmployees, function ($emp) use ($id)
                             {
                                 return $emp['EmployeeID'] == $id;
                             });
        foreach ($data as $dat)
        {
            return $dat['Email'];
        }
    }
    public function getEmployeeBS($id)
    {
        $connection = Yii::$app->getDb();
        $Employee = $connection->createCommand("
SELECT `EmployeeID`, `FullName`,`BankAccountNumber`,`Email`, `Salary` from `employee` WHERE EmployeeID NOT IN (SELECT MIN(EmployeeID) FROM employee) AND IsActive = 1");
        $listEmployees = $Employee->queryAll();
        // global $listEmployees;
        $data = array_filter($listEmployees, function ($emp) use ($id)
                             {
                                 return $emp['EmployeeID'] == $id;
                             });
        foreach ($data as $dat)
        {
            return $dat['Salary'];
        }
    }
    public function getEmployeeBankAccount($id)
    {
        global $listEmployees;
        $data = array_filter($listEmployees, function ($emp) use ($id)
                             {
                                 return $emp['EmployeeID'] == $id;
                             });
        foreach ($data as $dat)
        {
            return ($dat['BankAccountNumber'] != NULL ? $dat['BankAccountNumber'] : "");
        }
    }
    public function getEmployeeAbsentDays($id)
    {
        $WorkedHours = 0;
        $WorkingHours = 0;
        $AbsentDays = 0;
        $workedTimeTotal = 0;
        $WorkedDays = 0;
        $OTHours = 0;
        $InsuffHours = 0; $InsuffHourSalary = 0; $InsuffHourSalaryDeduction = 0; $AbsentHours = 0; $TotalWorkingHourAfter = 0; $InsuffHours = 0; $InsuffHourSalary = 0; $InsuffHourSalaryDeduction = 0;
        $firstDateOfPrevMonth = date('Y-m-d', strtotime('-1 day', strtotime('first day of last month')));
        $lastDateOfPrevMonth = date('Y-m-d', strtotime('last day of last month'));
        $TD = date_diff(date_create($firstDateOfPrevMonth) , date_create($lastDateOfPrevMonth));
        $TotalDays = intval($TD->format("%a"));
        $Saturdays = $this->getSaturdays($firstDateOfPrevMonth, $lastDateOfPrevMonth);
        // $connection = Yii::$app->getDb();
        // $Employee = $connection->createCommand("
        // SELECT `EmployeeID`, `FullName`,`BankAccountNumber`,`Email`, `Salary` from `employee` WHERE EmployeeID NOT IN (SELECT MIN(EmployeeID) FROM employee) AND IsActive = 1");
        // $listEmployees = $Employee->queryAll();
        // $queryAttendance = sprintf("SELECT DISTINCT(AttnDate), EmployeeID, WorkedTime from attendance WHERE `AttnDate` BETWEEN '%s' and '%s' and IsActive = 1", $firstDateOfPrevMonth, $lastDateOfPrevMonth);
        // $Attendance = $connection->createCommand($queryAttendance);
        // $listAttendance = $Attendance->queryAll();
        // $Holiday = $connection->createCommand("SELECT COUNT(*) as Days FROM holiday WHERE MONTH(Day) = MONTH(DATE_ADD(Now(), INTERVAL -1 MONTH))");
        // $Holidays = $Holiday->queryOne();
        // $Setting = $connection->createCommand("SELECT `SalaryAmendmentID`,`TotalWorkingHourPerDay`,`MaximumOTHoursPerDay`,`MaximumPaidLeaveDays`,`SalaryCalcPercentOfOTHours`,`SalaryDeductionOfLessHours` FROM `salarycalculationamendment` WHERE `IsActive` = 1");
        // $listSetting = $Setting->queryAll();
        // $AttendanceSetting = $connection->createCommand("SELECT * FROM `payrollattendance` WHERE `IsActive` = 1");
        // $listAttendanceSetting = $AttendanceSetting->queryAll();
        global $listAttendanceSetting;
        global $listAttendance;
        global $Holidays;
        global $listSetting;
        global $listAttendanceSetting;
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
                $workedTimeTotal += $this->timeToSec($attnData['WorkedTime']);
                $WorkedDays++;
            }
        }
        $BasSal = $this->getEmployeeBS($id); //Basic Salary Of the employee
        $WorkingDays = $TotalDays - $Saturdays - $Holidays['Days'];
        $WorkingHours = $WorkingDays * $TotalWorkingHourPerDay;
        $OneHourSalary = $BasSal  / $WorkingHours;
        $TotalAbsentDays = ($WorkedDays > $WorkingDays)? 0: $WorkingDays - $WorkedDays;
        $AbsentDaysWithDeduction = (($TotalAbsentDays) > $MaximumPaidLeaveDays) ? $TotalAbsentDays - $MaximumPaidLeaveDays : 0;
        $AbsentHours = ($AbsentDaysWithDeduction * $TotalWorkingHourPerDay);
        $AbsentDeduction = number_format((float)$AbsentHours * ($OneHourSalary*1.5), 2, '.', '');
        $WorkedTimeSecs = $this->timeToSec($this->getTime($workedTimeTotal));
        $WorkingTimeSecs = $this->timeToSec($WorkingHours . ":00:00");
        $OTHours = $WorkedTimeSecs - $WorkingTimeSecs;
        $OT = ($OTHours > $WorkingHours) ? $this->getTime($OTHours) : 0;
        $OTHourSalary = ($OneHourSalary * 1.5) * $this->decimalHours($OT);
        $OTBonus = number_format((float)$OTHourSalary, 2, '.', '');
        if ($AbsentDays - $MaximumPaidLeaveDays  == 0)
        {
            if ($this->timeToSec($WorkingHours . ":00:00") > $workedTimeTotal)
            {
                $InsuffHours = $this->getTime($this->timeToSec($WorkingHours . ":00:00") - $workedTimeTotal);
                $InsuffHourSalary = ($OneHourSalary * 1.5) * $this->decimalHours($InsuffHours);
                $InsuffHourSalaryDeduction = number_format((float)$InsuffHourSalary, 2, '.', '');
            }
        }
        if ($AbsentDays- $MaximumPaidLeaveDays > 0)
        {
            if ($this->timeToSec($WorkingHours . ":00:00") > $workedTimeTotal)
            {
                $AbsentHours = $this->timeToSec($MaximumPaidLeaveDays*$TotalWorkingHourPerDay.":00:00");
                $TotalWorkingHourAfter = $this->timeToSec($WorkingHours . ":00:00")-$AbsentHours;
                $InsuffHours = $this->getTime( $TotalWorkingHourAfter - $workedTimeTotal);
                $InsuffHourSalary = ($OneHourSalary * 1.5) * $this->decimalHours($InsuffHours);
                $InsuffHourSalaryDeduction = number_format((float)$InsuffHourSalary, 2, '.', '');
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'AbsDays' => $TotalAbsentDays, 
            'AbsDeduct' => $AbsentDeduction,
            'OTHours' => $OT,
            'OTBonus' => $OTBonus,
            'InsufficientHours' => $InsuffHours,
            'InsufficientHoursSalaryDeduction' => $InsuffHourSalaryDeduction,
            'WorkedDays' => $WorkedDays,
            'WorkingDays' => $WorkingDays,
            'TotalDays' => $TotalDays,
            'firstDateOfPrevMonth' => $firstDateOfPrevMonth,
            'lastDateOfPrevMonth' => $lastDateOfPrevMonth
        ];
    }
    public function getSaturdays($start, $end)
    {
        $current = $start;
        $count = 0;
        while ($current != $end)
        {
            if (date('l', strtotime($current)) == 'Saturday')
            {
                $count++;
            }
            $current = date('Y-m-d', strtotime($current . ' +1 day'));
        };
        return $count;
    }
    public function getEmployeAdvance($id)
    {
        $date = date('Y-m-d');
        $PreviousMonth = date('m', strtotime('-1 month', strtotime($date)));
        global $listAdvance;
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
                $AdvanceID = $la['AdvanceID'];
                $EmployeeID = $la['EmployeeID'];
                $month = $la['Month'];
                $advanceAmount = $la['Amount'];
                if ($month == null)
                {
                    $deductionAmount = $advanceAmount;
                    return $deductionAmount;
                }
                else
                {
                    return ($month == $PreviousMonth ? $advanceAmount : "0");
                }
            }
        }
    }
    public function setUnsetAdvancePaidFlag($employeeID, $action, $month, $year)
    {
        if ($action != NULL && $employeeID != NULL && $month != NULL) {
            $Ispaid = (strtolower($action) == "deduct")?1:0;
            try {
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = sprintf("UPDATE `advance` SET `IsPaid` = '%d' WHERE `advance`.`EmployeeID` = '%d' AND `advance`.`Month` = '%d' AND `advance`.`Year` = '%d' AND `advance`.`IsActive` = '1'", $Ispaid, $employeeID, $month, $year);
                $result = $connection->createCommand($qry)/*->getRawSql()*/;
                $res = $result->execute();
                $return =  (($res == true) ? 'true' : 'false');
                return $return;                    
            } catch (Exception $e) {
                return $e;
            }
        }                
    }
    public function actionSavepayroll()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                if(!(isset($_POST['array']))){return '{"result":false,"message":"No Employee Selected."}'; }else{$PayrollArray = $_POST['array'];}                
                $Year = $_POST['year'];
                $Month = $_POST['month'];
                $EmailMessage = $_POST['message'];
                if ($this->checkForMonthPayrollIfExist($Year, $Month) == true)
                {
                    return '{"message":"Payroll of ' . date("F", mktime(0, 0, 0, $Month, 10)) . ' Already Exist."}';
                }
                else
                {
                    if (sizeof($PayrollArray) > 0)
                    {
                        $i = 0;
                        $insert = '';
                        $loggedInUserID = Yii::$app->session['UserID'];
                        foreach ($PayrollArray as $key => $PayrollArr)
                        {
                            //check if the payroll of this employee of this month and year exist
                            //if exist then continue the loop else keep the loop going
                            $getLog = $this->getPayrollLogIfExist($PayrollArr['EmployeeID'], $Year, $Month);
                            if($getLog === false){
                                continue;
                            }
                            if ($i != 0) $insert .= ',';
                            $insert .= '(';
                            $insert .= "'" . $PayrollArr['EmployeeID'] . "',";
                            $insert .= "'" . $PayrollArr['Employee Name'] . "',";
                            $insert .= "'" . $Year . "',";
                            $insert .= "'" . $Month . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Basic Salary']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['PF']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Gratuity']). "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Allowance']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Grade']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Incentive']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Bonus']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Total Allowance']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['PF Deduction']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['CIT Deduction']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Total Deduction']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Income']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Absent Days']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Absent Deduction']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Gross Income']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['SST']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Other TAX']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Net Income']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Advance Deduction']) . "',";
                            $insert .= "'" . $this->extract_numbers($PayrollArr['Payable Amount']) . "',";
                            $insert .= "'" . $PayrollArr['Remarks'] . "',";
                            $insert .= "'" . Date('Y-m-d') . "',";
                            $insert .= "'" . $loggedInUserID . "'";
                            $insert .= ')';
                            if($this->extract_numbers($PayrollArr['Payable Amount'])>0){
                                $payAdvance = $this->setUnsetAdvancePaidFlag($PayrollArr['EmployeeID'], $action="deduct", $Month, $Year);
                            }
                            $sendEmail = $this->sendEmailNotification($Year, $Month, $EmailMessage, $PayrollArr);
                            if($sendEmail == true){
                                $setLog = $this->setEmpPayrollLog($Year, $Month, $PayrollArr['EmployeeID'], $loggedInUserID);
                                if ($setLog == TRUE) {
                                    $i++;
                                }
                                else{
                                    break;
                                    return '{"status":false, "message":"Log Not Saved."}';
                                }
                            }else{
                                break;
                                return '{"status":false, "message":"Email Not Sent."}';
                            }
                        }
                        $query = new Query();
                        $connection = Yii::$app->getDb();
                        $qry = sprintf("INSERT INTO `payroll` (`EmployeeID`,`FullName`, `Year`, `Month`, `BasicSalary`, `PF`, `Gratuity`, `Allowance`, `Grade`, `Incentive`, `Bonus`, `TotalAllowance`, `PFDeduction`, `CITDeduction`, `TotalDeduction`, `Income`, `AbsentDays`, `AbsentDeduction`, `GrossIncome`, `SST`, `OtherTAX`, `NetIncome`, `AdvanceDeduction`, `PayableAmount`, `Remarks`, `CreatedDate`, `CreatedBy`) VALUES %s;", $insert);
                        $result = $connection->createCommand($qry) /*->getRawSql()*/;
                        $res = $result->execute();
                        $return = $res == true ? '{"result":true,"message":"Saved successfully"}' : '{"result":false,"message":"Not Saved successfully"}';
                        return $return;
                    }
                }
            }
            catch(Exception $e)
            {
                return $e;
            }
        }
    }
    public function checkForMonthPayrollIfExist($year, $month)
    {
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT count(*) as flag FROM `listitems` WHERE `IsActive` = 1 and `Type` = 'Payroll' and `Title` = '%d' and `Value` = '%d'", $year, $month);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        return (($res['flag'] == 1) ? true : false);
    }
    public function setEmpPayrollLog($year, $month, $employeeID, $loggedInUserID){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("INSERT INTO `payrolllog` (`EmployeeID`, `Year`, `Month`,`CreatedBy`) VALUES ('%d', '%d', '%d','%d');",$employeeID, $year, $month,$loggedInUserID);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->execute();
        $return =  (($res == 1) ? true : false);
        return $return;
    }
    public function getPayrollLogIfExist($empID, $year, $month){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT * FROM `payrolllog` WHERE IsActive = 1 and `EmployeeID` = '%d' and `Month` = '%d' and `Year` = '%d'",$empID, $month, $year);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryAll();
        $return =  (($res == null) ? true : false);
        return $return;
    }    
    public function checkEmployeeIfDenied($empID){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT * FROM `employeepayrolldeny` WHERE EmployeeID = %d and IsActive = 1",$empID);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryAll();
        $return =  (($res == null) ? false : true);
        return $return;
    }
    public function getEmppayroll($employeeID, $year, $month)
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                $payrollEmployees = Payroll::find()->where(['EmployeeID'=>$employeeID, 'Year' => $year, 'Month' => $month, 'IsActive' => 1])->all();
                $html = "";
                foreach ($payrollEmployees as $key => $payrollEmployee)
                {
                    if($payrollEmployee['IsPaid'] == 1 && $payrollEmployee['IsProcessed'] == 1 ){
                        $html .= '<tr class="paid" attr-id = '.$payrollEmployee['EmployeeID'].'>';
                        $html .= "<td>Paid</td>";  
                    }else{
                        $html .= '<tr class="processed" attr-empid = '.$payrollEmployee['EmployeeID'].'>';
                        $html .= "<td><button attr-year=".$year." attr-month=".$month." title='Unprocess' class='unprocess-salary btn btn-default btn-xs'><span class = 'glyphicon glyphicon-minus'></span></button></td>";  
                    }
                    $html .= "<td>" . $payrollEmployee['FullName'] . "</td>";
                    $html .= "<td style='display: none;'> Bank Account Number </td>";
                    $html .= "<td style='display: none;'>".$payrollEmployee['EmployeeID']."</td>";
                    $html .= "<td style='display: none;'>Email</td>";
                    $html .= "<td>" . number_format($payrollEmployee['BasicSalary']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PF']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Gratuity']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Allowance']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Grade']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Incentive']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Bonus']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['TotalAllowance']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PFDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['CITDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['TotalDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['Income']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['AbsentDays']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['AbsentDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['GrossIncome']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['SST']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['OtherTAX']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['NetIncome']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['AdvanceDeduction']) . "</td>";
                    $html .= "<td>" . number_format($payrollEmployee['PayableAmount']) . "</td>";
                    $html .= "<td>" . $payrollEmployee['Remarks'] . "</td>";
                    $html .= "</tr>";
                }
                return $html;
            }
            catch(Exception $e){
            }
        }
    }
    public function actionPaypayroll()
    {
        if(!isset($_POST['array'])){
            return '{"result":false,"message":"No Employee Selected."}';
        }
        else{
            $Role = UserController::CheckRole("payroll");
            if ($Role == true)
            {
                $empArray = $_POST['array'];
                try
                {
                    $update = "";
                    $i=0;
                    foreach ($empArray as $key => $emparr) {                           
                        if ($i != 0) $update .= ',';
                        $update .= $emparr['EmployeeID'];
                        $i++; 
                    }
                    $query = new Query();
                    $connection = Yii::$app->getDb();
                    $qry = sprintf("UPDATE `payroll` SET `IsPaid`= 1 WHERE EmployeeID IN (%s) and IsActive = 1", $update);
                    $result = $connection->createCommand($qry)/* ->getRawSql()*/;
                    $res = $result->execute();
                    $return = $res == true ? '{"result":true,"message":"Paid Successfully"}' : '{"result":false,"message":"Paying Failed"}';
                    return $return;
                }
                catch(Exception $e){
                    return $e;
                }
            }
        }
    }
    public function extract_numbers($string)
    {
        return preg_replace("/[^0-9]/", '', $string);
    }
    public function sendEmailNotification($year, $month, $message, $payroll)
    {
        $employeeEmail = $payroll['Email'];
        $Subject = "About The Salary of ".date("F", mktime(0, 0, 0, $month, 10))."-".$year;
        $EmailBody = '
            <div style="margin:25px auto;padding:0 25px;max-width: 100%;width: 600px">
            <strong>Dear, <em>'.$payroll['Employee Name'].'</em></strong>
            <p style="text-align:center"><strong>Subject :<em> About The Salary of '.date("F", mktime(0, 0, 0, $month, 10)).'-'.$year.'.</em></strong></p>
            <p style="text-align: justify;"><span style="width:30px;display: inline-block;"></span>We hereby notify you that your salary sheet has been processed today. Please find your salary slip of '.date("F", mktime(0, 0, 0, $month, 10)).'.</p>
            <p style="font-size: 16px;font-weight: bold;margin-bottom: 5px;text-align: center;text-decoration: underline;">Your salary slip is as follows.</p>
            <div>
            <table style="border: 2px solid #000;width: 100%;font-size: 16px;border-collapse: collapse;">
            <tr style="background: #000;color: #fff;font-size: 20px;font-weight: bold;text-align:center">
            <th style="padding:5px;border:1px solid #ccc;">Particulars<br></th>
            <th style="padding:5px;border:1px solid #ccc;">Amount</th>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;">Basic Salary<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Basic Salary"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;">Provident Fund</td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["PF"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;">Gratuity</td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Gratuity"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;border-top:2px solid #000;border-left:2px solid #000;">Allowance</td>
            <td style="padding:5px;border:1px solid #ccc;border-right:2px solid #000;border-top:2px solid #000">'.$payroll["Allowance"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;border-left:2px solid #000;">Grade</td>
            <td style="padding:5px;border:1px solid #ccc;border-right:2px solid #000;">'.$payroll["Grade"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;border-left:2px solid #000;">Incentive</td>
            <td style="padding:5px;border:1px solid #ccc;border-right:2px solid #000;">'.$payroll["Incentive"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;border-left:2px solid #000;">Bonus</td>
            <td style="padding:5px;border:1px solid #ccc;border-right:2px solid #000;">'.$payroll["Bonus"].'</td>
            </tr>
            <tr style="background: #666;color: #fff;">
            <td style="padding:5px;border:1px solid #ccc;border-left:2px solid #000;border-bottom:2px solid #000;">Total Allowance<br></td>
            <td style="padding:5px;border:1px solid #ccc;border-right:2px solid #000;border-bottom:2px solid #000;">'.$payroll["Total Allowance"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;">PF Deduction<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["PF Deduction"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;">CIT Deduction<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["CIT Deduction"].'</td>
            </tr>
            <tr style="background: #666;color: #fff;">
            <td style="padding:5px;border:1px solid #ccc;border-left:2px solid #000;border-bottom:2px solid #000;">Total Deduction<br></td>
            <td style="padding:5px;border:1px solid #ccc;border-left:2px solid #000;border-bottom:2px solid #000;">'.$payroll["Total Deduction"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;">Income</td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Income"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;">Absent Days<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Absent Days"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;">Absent Deduction<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Absent Deduction"].'</td>
            </tr>
            <tr style="background: #666;color: #fff;">
            <td style="padding:5px;border:1px solid #ccc;">Gross Income<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Gross Income"].'</td>
            </tr>
            <tr style="background:#eee;">
            <td style="padding:5px;border:1px solid #ccc;">SST</td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["SST"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;">Other TAX<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Other TAX"].'</td>
            </tr>
            <tr style="background: #666;color: #fff;">
            <td style="padding:5px;border:1px solid #ccc;">Net Income<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Net Income"].'</td>
            </tr>
            <tr>
            <td style="padding:5px;border:1px solid #ccc;">Advance Deduction<br></td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Advance Deduction"].'</td>
            </tr>
            <tr style="background: #000;color: #fff;font-size: 20px;font-weight: bold;text-align:center;">
            <td style="padding:5px;border:1px solid #ccc;">Total</td>
            <td style="padding:5px;border:1px solid #ccc;">'.$payroll["Payable Amount"].'</td>
            </tr>
            </table>
            </div>
            <p>*If you have any doubt regarding the salary slip or have any question do not hesitate to contact us.</p>
            <p>'.$message.'</p>
            <p>
            Thank You<br/>
            Account Section<br/>
            Top Nepal International Pvt. Ltd.
            </p>
            </div>';
        $sendMail = Yii::$app->email->sendemail($employeeEmail, $EmailBody, $Subject);
        $return = $sendMail = TRUE?true:false;
        return $return;
    }
    public function actionUnprocess(){
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                $EmployeeID = $_POST['EmployeeID'];
                $Month = $_POST['Month'];
                $Year = $_POST['Year'];
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry1 = sprintf("UPDATE `payroll` SET `IsActive` = '0' WHERE `payroll`.`EmployeeID` = %d AND `payroll`.`Month` = %d AND `payroll`.`Year` = %d AND `payroll`.`IsActive` = 1", $EmployeeID, $Month, $Year);
                $qry2 = sprintf("UPDATE `payrolllog` SET `IsActive` = '0' WHERE `payrolllog`.`EmployeeID` = %d AND `payrolllog`.`Year` = %d AND `payrolllog`.`Month` = %d AND `payrolllog`.`IsActive` = 1", $EmployeeID, $Year, $Month);
                $result1 = $connection->createCommand($qry1)/* ->getRawSql()*/;
                $result2 = $connection->createCommand($qry2)/* ->getRawSql()*/;
                $res1 = $result1->execute();
                if($res1 == TRUE)
                {
                    $res2 = $result2->execute();
                    $this->setUnsetAdvancePaidFlag($EmployeeID, $action="uneduct", $Month, $Year);
                }
                $return = ($res1 == TRUE && $res2 == TRUE) ? '{"result":true,"message":"Unpaid Successfully"}' : '{"result":false,"message":"Unpaid Failed"}';
                return $return;
            }
            catch(Exception $e)
            {
                return '{"result":false,"message":"'.$e.'"}';  
            }
        }
    }
    function timeToSec($string)
    {
        list($hour, $min, $sec) = array_pad(explode(':', $string, 3) , -3, NULL);
        return $hour * 3600 + $min * 60 + $sec;
    }
    function getTime($duration)
    {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        $seconds = $duration % 60;
        return "$hours:$minutes:$seconds";
    }
    function decimalHours($time)
    {
        $hms = array_pad(explode(":", $time,3), -3, NULL);
        return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
    }
}