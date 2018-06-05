<?php
namespace backend\modules\user\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\user\models\Listitems;
use backend\modules\user\models\ListitemsSearch;
use backend\modules\user\models\Salarycalculationamendment;
use backend\modules\leave\models\Leave;
class SettingsController extends \yii\web\Controller
{
    public function __construct($id, $module, $config = [])
    {
        $menus=Yii::$app->session['Menus'];
        $menusarray=(explode(",",$menus)); 
        parent::__construct($id, $module, $config);
        $flag= in_array( "settings" ,$menusarray )?true:false;
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
                'only' => ['index','create', 'update', 'delete', 'view'],
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
        $model = new Listitems();
        $StockUnit = Listitems::find()->where(['IsActive'=>1, 'Type'=> "stockunit", 'ParentID'=>0])->all();
        $StockUnitParent = (count($StockUnit) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($StockUnit, 'ListItemID', 'Title');
        $LeaveType = Listitems::find()->select('ListItemID, Title')->where(['Type' => 'leavetype', 'IsActive' => 1])->all();
        return $this->render('index',[
            'model' => $model,
            'LeaveType' => $LeaveType,
            'StockUnitParent' => $StockUnitParent,
        ]);
    }
    public function actionSalaryamendment()
    {
        $model = new Salarycalculationamendment();
        return $this->render('salarycalculation',[
            'model' => $model,
        ]);
    }
    public function actionSavedata()
    {
        $Role = UserController::CheckRole("settings");
        if ($Role == true) {
            $Identity = $_POST["identity"];
            if($Identity == 0){
                $model = new Listitems();
            }else{
                $model = Listitems::findOne($Identity);
            }
            $Title =  $_POST["title"];
            $Type = $_POST["type"];
            $Value = $_POST["value"];
            $Options = $_POST["options"];
            $model->Type = $Type;
            $model->Title = $Title;
            $model->Value = $Value;
            $model->Options = $Options;
            $model->CreatedBy = Yii::$app->user->id;
            $model->CreatedDate = Date('Y-m-d H:i:s');
            if($model->save()){
                return '{"result":true,"message":"saved successfully"}';
            }
        }         
    }
    public function actionRetrivedata(){
        $Role =UserController::CheckRole("settings");
        if ($Role == true) {
            $PostType = $_POST['type'];
            if($PostType=="stockunit"){
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand( "SELECT * FROM listitems U left join (SELECT ListItemID ID,Title Parent FROM listitems where Type='stockunit' and ParentID=0) As P on U.`ParentID` = P.`ID` where U.`Type` ='stockunit'");
                $Result = $command->queryAll();
            }
            else{
                $Result = Listitems::find()->where(['type'=>$PostType])->all();
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $Result;
        } 
    }
    public function actionRetriveaccrue(){
        $Role =UserController::CheckRole("settings");
        if ($Role == true) {
            $PostType = $_POST['type'];
            if($PostType=="accrue"){
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand( "SELECT A.`Type`, L.`Title` as LeaveType, MONTHNAME(CONCAT('000-',A.`Value`,'-00')) as Month, A.`Title` as Year  from `listitems` A left join `listitems` L on L.`ListItemID` = cast(A.`Options` as SIGNED) WHERE A.`Type` = '".$PostType."';");
                $Result = $command->queryAll();
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $Result;
            }
        }
    }    
    public function actionRetrivepayrolltrack(){
        $Role =UserController::CheckRole("settings");
        if ($Role == true) {
            $PostType = $_POST['type'];
            if($PostType == "payroll"){
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand( "SELECT Type, Title as Year, MONTHNAME(CONCAT('000-',Value,'-00')) as Month FROM listitems WHERE Type = '".$PostType."';");
                $Result = $command->queryAll();
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $Result;
            }
        }
    }
    public function actionSaveamendmentdata(){
        $Role =UserController::CheckRole("settings");
        if ($Role == true) {
            try {
                $Name = $_POST["Name"];
                $WorkHourPerDay = $_POST["WorkHourPerDay"];
                $PaidLeavePerMonth = $_POST["PaidLeavePerMonth"];
                $AllowedOTHours = $_POST["AllowedOTHours"];
                $OthoursSalaryCalculation = $_POST["OthoursSalaryCalculation"];
                $LessWorkHourSalaryCalculation = $_POST["LessWorkHourSalaryCalculation"];
                if ($Name!=NULL && $WorkHourPerDay != NULL && $PaidLeavePerMonth != NULL && $AllowedOTHours != NULL && $OthoursSalaryCalculation != NULL && $LessWorkHourSalaryCalculation != NULL) {
                    $model = new Salarycalculationamendment();
                    $model->AmendmentName = $Name;
                    $model->TotalWorkingHourPerDay = $WorkHourPerDay;
                    $model->MaximumPaidLeaveDays = $PaidLeavePerMonth;
                    $model->MaximumOTHoursPerDay = $AllowedOTHours;
                    $model->SalaryCalcPercentOfOTHours = $OthoursSalaryCalculation;
                    $model->SalaryDeductionOfLessHours = $LessWorkHourSalaryCalculation;
                    $model->CreatedBy = Yii::$app->session['UserID'];
                    $model->CreatedDate = Date('Y-m-d H:i:s');
                    //echo "<pre>"; print_r($model); die();
                    $return = ($model->save() == TRUE)?'{"result":true,"message":"saved successfully"}':'{"result":false,"message":"Not Saved"}';
                    return $return;
                }     
            } catch (Exception $e) {  
                return "Caught Exception:".$e;
            } 
        }
    }
    public function actionGetamendments()
    {
        $Role = UserController::CheckRole("settings");
        if ($Role == true)
        {
            try
            {
                $Amendment = Salarycalculationamendment::find()->all();
                $html = "";
                if (sizeof($Amendment) < 1) {
                    $html .= "<tr><td align='center' colspan='7'>No Data Available</td></td>";
                } else {        
                    foreach($Amendment as $key => $Amd)
                    {
                        $active = '<td><span class="hand edit" data-id=' . $Amd["SalaryAmendmentID"] . '>Deactivate</span></td>';
                        $inactive = '<td>Deactivated</td>';
                        $html.= "<tr>";
                        $html.= "<td>" . $Amd['AmendmentName'] . "</td>";
                        $html.= "<td>" . $Amd['TotalWorkingHourPerDay'] . "</td>";
                        $html.= "<td>" . $Amd['MaximumPaidLeaveDays'] . "</td>";
                        $html.= "<td>" . $Amd['MaximumOTHoursPerDay'] . "</td>";
                        $html.= "<td>" . $Amd['SalaryCalcPercentOfOTHours'] . "</td>";
                        $html.= "<td>" . $Amd['SalaryDeductionOfLessHours'] . "</td>";
                        $html.= $Amd['IsActive'] == 1?$active:$inactive;
                        $html.= "</tr>"; 
                    }
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['html' => $html];
            }
            catch(Exception $e)
            {
                return $e;
            }
        }
    }
    public function actionDeactivateamendment(){
        $Role = UserController::CheckRole("settings");
        if ($Role == true)
        {
            try
            {
                $ID = $_POST['ID'];
                if($ID != NULL)
                {
                    $query = new Query();
                    $connection = Yii::$app->getDb();
                    $qry = sprintf("UPDATE `salarycalculationamendment` SET `IsActive` = '0' WHERE `salarycalculationamendment`.`SalaryAmendmentID` = %d;", $ID);
                    $result = $connection->createCommand($qry)/* ->getRawSql()*/;
                    $res = $result->execute();
                    $return = $res == true ? '{"result":true,"message":"Deactivated Successfully"}' : '{"result":false,"message":"Deactivation Failed"}';
                    return $return;
                }
            }
            catch(Exception $e)
            {
            }
        }
    }
    /*
*Leave Accrue Setting
*/
    public function actionLeaveaccrue(){
        $LeaveType = Listitems::find()->select('ListItemID, Title, Value')->where(['Type' => 'leavetype', 'IsActive' => 1])->all();
        $quer = new Query();
        $EmployeeLeave = $quer->select('E.`FullName`, L.`LeaveID`, L.`EmployeeID`, LI.`Title`, L.`Earned`, L.`Balance`, L.`CreatedBy`, L.`CreatedDate`, L.`UpdatedBy`, L.`UpdatedDate`, L.`IsActive`, L.`IsDeleted`, L.`Year`')->from('leave L')->leftJoin('employee E','L.EmployeeID = E.EmployeeID')->where(['E.IsActive' => 1])->leftJoin('listitems LI','LI.ListItemID = L.LeaveTypeID')->where(['E.IsActive' => 1])->all();
        return $this->render('leaveaccrue',[
            'LeaveType' => $LeaveType,
            'EmployeeLeave' => $EmployeeLeave
        ]);
    }
    public function actionLeaveinit(){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT EmployeeID , FullName FROM `employee` WHERE IsActive = '1' AND EmployeeID NOT IN (SELECT DISTINCT(`EmployeeID`) FROM `leave`)");
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $resultEmp = $result->queryAll();
        $LeaveType = Listitems::find()->select('ListItemID, Title, Value')->where(['Type' => 'leavetype', 'IsActive' => 1])->all();
        return $this->render('leaveinitilize',[
            'resultEmp' => $resultEmp,
            'LeaveType' => $LeaveType
        ]);
    }
    public function actionSaveleaveinit(){
        $Role =UserController::CheckRole("settings");
        if ($Role == true) {
            if(isset($_POST['EmployeeID'], $_POST['LeaveTypeID'], $_POST['Month'], $_POST['Year'], $_POST['Days'])){
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = sprintf("INSERT INTO `leave` (`EmployeeID`, `LeaveTypeID`, `Earned`, `Balance`, `Year`, `CreatedBy`, `CreatedDate`) VALUES ('%d', '%d', '%d', '%d', '%d', '%d', '%s')",$_POST['EmployeeID'], $_POST['LeaveTypeID'], $_POST['Days'], $_POST['Days'], $_POST['Year'], Yii::$app->session['UserID'], Date('Y-m-d'));
                $result = $connection->createCommand($qry) /*->getRawSql()*/;
                $res = $result->execute();
                if($res == TRUE){
                    $connection1 = Yii::$app->getDb();
                    $qry1 = sprintf("INSERT INTO `leavedetails` (`EmployeeID`, `LeaveTypeID`, `Month`, `Year`, `Accrue`, `CreatedDate`, `CreatedBy`, `Remarks`) VALUES ('%d', '%d', '%d', '%d', '%d', '%s', '%d', 'Initialized');",$_POST['EmployeeID'], $_POST['LeaveTypeID'], $_POST['Month'], $_POST['Year'], $_POST['Days'], Date('Y-m-d'),Yii::$app->session['UserID']);
                    $result1 = $connection1->createCommand($qry1) /*->getRawSql()*/;
                    $res1 = $result1->execute();
                $return = ($res1 == TRUE)?'{"result":true,"message":"Initilized Successfully."}':'{"result":true,"message":"Not Initilized."}';
                return $return;
                }else{
                    return '{"result":true,"message":"Not Initilized."}';
                }
            }
        }
    }
    public function actionSaveleaveaccrue(){
        $Role = UserController::CheckRole("settings");
        if ($Role == true)
        {
            $LeaveTypeID = $_POST['LeaveTypeID'];
            $Days = $_POST['Days'];
            $Month = $_POST['Month'];
            $Year = $_POST['Year'];
            try {
                /*
*Check if leave of the month already accrued
*/
                $checkFlag = $this->checkforaccrual($LeaveTypeID, $Month, $Year);
                if($checkFlag != TRUE){
                    $Employees = $this->Getemployees();
                    if(!empty($Employees)){
                        $i = 0;
                        $insert = '';
                        $loggedInUserID = Yii::$app->session['UserID'];
                        foreach ($Employees as $key => $Emp) {
                            if ($i != 0) $insert .= ',';
                            $insert .= '(';
                            $insert .= "'" . $Emp['EmployeeID'] . "',";
                            $insert .= "'" . $LeaveTypeID . "',";
                            $insert .= "'" . $Month . "',";
                            $insert .= "'" . $Year . "',";
                            $insert .= "'" . $Days . "',";
                            $insert .= "'" . Date('Y-m-d') . "',";
                            $insert .= "'" . $loggedInUserID . "',";
                            $insert .= "'Accrued Of " . date("F", mktime(0, 0, 0, $Month, 10)) . ",".$Year.".'";
                            $insert .= ')';
                            $i++;
                        }
                        $query = new Query();
                        $connection = Yii::$app->getDb();
                        $qry = sprintf("INSERT INTO `leavedetails` (`EmployeeID`, `LeaveTypeID`, `Month`, `Year`, `Accrue`, `CreatedDate`, `CreatedBy`,`Remarks`) VALUES %s;", $insert);
                        $result = $connection->createCommand($qry) /*->getRawSql()*/;
                        $res = $result->execute();
                        if($res == TRUE){
                            $query = new Query();
                            $connection = Yii::$app->getDb();
                            $qry = sprintf("UPDATE `leave` SET `UpdatedDate`= CURDATE(), `UpdatedBy` = ".$loggedInUserID.", `Earned` = Earned + ".$Days.", `Balance` = Balance + ".$Days." WHERE `LeaveTypeID` ='".$LeaveTypeID."' AND `EmployeeID` IN(SELECT `EmployeeID` FROM `employee` WHERE IsActive = '1')");
                            $resultacc = $connection->createCommand($qry);
                            $resacc = $resultacc->execute();
                            if($resacc == TRUE){
                                $trackFlag = $this->trackAccrue($LeaveTypeID, $Month, $Year);
                            }else{
                                return '{"result":false,"message":"Not Accrued successfully"}';
                            }
                            $return = $trackFlag == true ? '{"result":true,"message":"Accrued successfully"}' : '{"result":false,"message":"Not Accrued successfully"}';
                            return $return;
                        }else{
                            return '{"result":false,"message":"Not Accrued successfully"}';
                        }
                    }
                }else{
                    return '{"result":false,"message":"Accrual For '.date("F", mktime(0, 0, 0, $Month, 10)).' Already Done"}';
                }
            } catch (Exception $e) {
                return "Caught Exception".$e;
            }
        }
    }
    public function Getemployees(){
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT `EmployeeID` FROM `employee` WHERE `IsActive` = 1");
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryAll();
        return $res;
    }
    public function checkforaccrual($leavetype, $month, $year){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT COUNT(*) as flag FROM `listitems` WHERE `Type` = 'accrue' AND `Title` = '%d' AND `Value` = '%d' AND `Options` = '%d' AND `IsActive` = '1'",$year, $month, $leavetype);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        return (($res['flag'] == 1) ? true : false);
    }
    public function trackAccrue($leavetype, $month, $year){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $Date = Date("Y-m-d");
        $ID = Yii::$app->session['UserID'];
        $qry = sprintf("INSERT INTO `listitems` (`Type`, `Title`, `Value`, `ParentID`, `Options`, `CreatedDate`, `CreatedBy`) VALUES ('accrue', '%d', '%d', '0', '%d', '%s', '%d')",$year, $month, $leavetype, $Date, $ID);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->execute();
        return (($res == 1) ? true : false);
    }
}
