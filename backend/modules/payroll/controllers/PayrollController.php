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
            $this->redirect(Yii::$app
                ->urlManager->baseUrl . '/dashboard');
            return false;
        }
    }

    public function behaviors()
    {
        return ['access' => ['class' => \yii\filters\AccessControl::className() , 'only' => ['index'], 'rules' => [['allow' => true, 'roles' => ['@'], ], ], ], 'verbs' => ['class' => VerbFilter::className() , 'actions' => ['delete' => ['POST'], ], ], ];
    }

    public function actionIndex()
    {
        $this->layout = '@backend/views/layouts/paymain';
        $date = date('Y-m-d');
        $newdate = strtotime('-1 month', strtotime($date));
        $sentMonth = date('m', $newdate);
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
            $firstDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-1 month"));
            $lastDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-t"))) . "-1 month"));
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
                    $Employee = $connection->createCommand("
				    SELECT `EmployeeID`, `FullName`,`BankAccountNumber`, `Salary` from `employee` WHERE IsActive = 1");
                    global $listEmployees;
                    $listEmployees = $Employee->queryAll();

                    $EmployeePayroll = $connection->createCommand("
				    SELECT `EmployeeID`, `IsAllowance`, `AllowanceTitle`, `AllowanceAmount` FROM `employeepayroll` WHERE IsActive = 1");
                    global $listEmpAllowances;
                    $listEmpAllowances = $EmployeePayroll->queryAll();

                    $Allowances = $connection->createCommand("SELECT PayrollSettingID, IsAllowance, Title as AllowanceTitle  FROM `payrollsetting` WHERE `IsActive` = 1 ");
                    global $listAllowances;
                    $listAllowances = $Allowances->queryAll();

                    $Attendance = $connection->createCommand("SELECT DISTINCT(AttnDate), EmployeeID from attendance WHERE `AttnDate` >= '" . $firstDateOfPrevMonth . "' and `AttnDate` <= '" . $lastDateOfPrevMonth . "'");
                    global $listAttendance;
                    $listAttendance = $Attendance->queryAll();
                    $Holiday = $connection->createCommand("SELECT COUNT(*) as Days FROM holiday WHERE MONTH(Day) = MONTH(DATE_ADD(Now(), INTERVAL -1 MONTH))");
                    global $Holidays;
                    $Holidays = $Holiday->queryOne();

                    $Advance = $connection->createCommand("SELECT * FROM `advance` where IsPaid = 0 and IsActive = 1");
                    global $listAdvance;
                    $listAdvance = $Advance->queryAll();
                    $html = "";

                    foreach ($this->getEmployeeIds() as $id)
                    {
                        $listing[$id] = $this->getEmployeeAllowances($id);
                        $html .= '<tr attr-empid="' . $id . '">';
                        $BasicSalary = $this->getEmployeeBS($id);
                        $html .= "<td>" . $this->getEmployeeName($id) . "</td>";
                        $html .= "<td style='display: none;'>" . $this->getEmployeeBankAccount($id) . "</td>";
                        $html .= "<td>" . $BasicSalary . "</td>";
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
                            $html .= "<td>" . $listAllow['AllowanceAmount'] . "</td>";
                            $AllowanceSum += $listAllow['AllowanceAmount'];
                        }

                        $html .= "<td>" . $AllowanceSum . "</td>";

                        foreach ($arrayDeductions as $listDeduc)
                        {
                            $html .= "<td>" . $listDeduc['AllowanceAmount'] . "</td>";
                            $DeductionSum += $listDeduc['AllowanceAmount'];
                        }

                        $html .= "<td>" . $DeductionSum . "</td>";
                        $income = $BasicSalary + $AllowanceSum - $DeductionSum;

                        $html .= "<td>" . $income . "</td>";

                        $AbsentValues = $this->getEmployeeAbsentDays($id);

                        $ADeduction = $AbsentValues["AbsDeduct"]; //Absent Deduction
                        $html .= "<td>" . $AbsentValues["AbsDays"] . "</td>";
                        $html .= "<td>" . $ADeduction . "</td>";

                        $GrossIncome = $income - $ADeduction;

                        $html .= "<td>" . floor($GrossIncome) . "</td>";

                        $SST = (1 / 100) * $GrossIncome;

                        $html .= "<td>" . floor($SST) . "</td>";

                        $OtherTAX = 0.00;

                        $html .= "<td>000</td>";

                        $NetIncome = $GrossIncome - ($SST + $OtherTAX);
                        $html .= "<td>" . floor($NetIncome) . "</td>";

                        $Advance = $this->getEmployeAdvance($id);
                        $html .= "<td>" . $Advance . "</td>";

                        $PayableAmount = $NetIncome - $Advance;
                        $html .= "<td>" . floor($PayableAmount) . "</td>";
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
                    $html .= '<tr>';
                    $html .= "<td>" . $payrollEmployee['FullName'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['BasicSalary'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['PF'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['Gratuity'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['CompensationAllowance'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['Grade'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['Incentive'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['Bonus'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['TotalAllowance'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['PFDeduction'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['CITDeduction'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['TotalDeduction'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['Income'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['SST'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['OtherTAX'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['NetIncome'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['AbsentDays'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['AbsentDeduction'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['AdvanceDeduction'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['PayableAmount'] . "</td>";
                    $html .= "<td>" . $payrollEmployee['Remarks'] . "</td>";
                    $html .= "</tr>";
                }

            }
            catch(Exception $e)
            {
                return $e = null ? "server Error" : $e;
            }
        }
        Yii::$app
            ->response->format = \yii\web\Response::FORMAT_JSON;
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
    public function getEmployeeBS($id)
    {
        global $listEmployees;
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
        $WorkedDays = 0;
        $WorkingDays = 0;
        $AbsentDays = 0;
        global $listAttendance;
        global $Holidays;
        $firstDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-1 month"));
        $lastDateOfPrevMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-t"))) . "-1 month"));
        $TD = date_diff(date_create($firstDateOfPrevMonth) , date_create($lastDateOfPrevMonth));
        $TotalDays = intval($TD->format("%a"));
        $Saturdays = $this->getSaturdays($firstDateOfPrevMonth, $lastDateOfPrevMonth);
        $data = array_filter($listAttendance, function ($emp) use ($id)
        {
            return $emp['EmployeeID'] == $id;
        });
        foreach ($data as $dat)
        {
            $WorkedDays++;
        }
        //calculating Absent
        $WorkingDays = $TotalDays - $Saturdays - $Holidays["Days"];
        $AbsentDays = $WorkingDays - $WorkedDays;
        $BasSal = $this->getEmployeeBS($id); //Basic Salary Of the employee
        $OneDaySalary = $BasSal / $WorkingDays;
        $AbsentDeduct = number_format((float)$OneDaySalary * $AbsentDays, 0, '.', '');
        Yii::$app
            ->response->format = \yii\web\Response::FORMAT_JSON;
        return ['AbsDays' => $AbsentDays, 'AbsDeduct' => $AbsentDeduct];

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

    public function actionSavepayroll()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                $PayrollArray = $_POST['array'];
                $Year = $_POST['year'];
                $Month = $_POST['month'];
                if ($this->checkForMonthPayrollIfExist($Year, $Month) == true)
                {
                    return '{"message":"Payroll of ' . date("F", mktime(0, 0, 0, $Month, 10)) . ' Already Exist."}';
                }
                else
                {
                    if ($PayrollArray != NULL)
                    {
                        $i = 0;
                        $insert = '';
                        $loggedInUserID = Yii::$app->session['UserID'];
                        foreach ($PayrollArray as $key => $PayrollArr)
                        {
                            if ($i != 0) $insert .= ',';
                            $insert .= '(';
                            $insert .= "'" . $PayrollArr['Employee Name'] . "',";
                            $insert .= "'" . $Year . "',";
                            $insert .= "'" . $Month . "',";
                            $insert .= "'" . $PayrollArr['Basic Salary'] . "',";
                            $insert .= "'" . $PayrollArr['PF'] . "',";
                            $insert .= "'" . $PayrollArr['Gratuity'] . "',";
                            $insert .= "'" . $PayrollArr['Allowance'] . "',";
                            $insert .= "'" . $PayrollArr['Grade'] . "',";
                            $insert .= "'" . $PayrollArr['Incentive'] . "',";
                            $insert .= "'" . $PayrollArr['Bonus'] . "',";
                            $insert .= "'" . $PayrollArr['Total Allowance'] . "',";
                            $insert .= "'" . $PayrollArr['PF Deduction'] . "',";
                            $insert .= "'" . $PayrollArr['CIT Deduction'] . "',";
                            $insert .= "'" . $PayrollArr['Total Deduction'] . "',";
                            $insert .= "'" . $PayrollArr['Income'] . "',";
                            $insert .= "'" . $PayrollArr['Absent Days'] . "',";
                            $insert .= "'" . $PayrollArr['Absent Deduction'] . "',";
                            $insert .= "'" . $PayrollArr['Gross Income'] . "',";
                            $insert .= "'" . $PayrollArr['SST'] . "',";
                            $insert .= "'" . $PayrollArr['Other TAX'] . "',";
                            $insert .= "'" . $PayrollArr['Net Income'] . "',";
                            $insert .= "'" . $PayrollArr['Advance Deduction'] . "',";
                            $insert .= "'" . $PayrollArr['Payable Amount'] . "',";
                            $insert .= "'" . $PayrollArr['Remarks'] . "',";
                            $insert .= "'" . Date('Y-m-d') . "',";
                            $insert .= "'" . $loggedInUserID . "'";

                            $insert .= ')';
                            $i++;
                        }

                        $query = new Query();
                        $connection = Yii::$app->getDb();
                        $qry = sprintf("INSERT INTO `payroll` (`FullName`, `Year`, `Month`, `BasicSalary`, `PF`, `Gratuity`, `CompensationAllowance`, `Grade`, `Incentive`, `Bonus`, `TotalAllowance`, `PFDeduction`, `CITDeduction`, `TotalDeduction`, `Income`, `AbsentDays`, `AbsentDeduction`, `GrossIncome`, `SST`, `OtherTAX`, `NetIncome`, `AdvanceDeduction`, `PayableAmount`, `Remarks`, `CreatedDate`, `CreatedBy`) VALUES %s;", $insert);
                        $result = $connection->createCommand($qry) /*->getRawSql()*/;
                        $res = $result->execute();
                        if($res == true){$checkedFlag = $this->setFlag($Year, $Month, $loggedInUserID );}                  
                        $return = $checkedFlag == true ? '{"result":true,"message":"Saved successfully"}' : '{"result":false,"message":"Not Saved successfully"}';
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
        $qry = sprintf("SELECT count(*) as flag FROM `listitems` WHERE `Type` = 'Payroll' and `Title` = '%d' and `Value` = '%d'", $year, $month);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        return (($res['flag'] == 1) ? true : false);
        echo $result;
    }

    public function setFlag($year, $month, $loggedInUserID)
    {
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("INSERT INTO `listitems` (`Type`, `Title`, `Value`, `ParentID`, `Options`,`CreatedBy`) VALUES ('payroll', '%d', '%d', '0', 'options', '%d')", $year, $month,$loggedInUserID);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->execute();
        $return =  (($res == 1) ? true : false);
        return $return;
    }
}