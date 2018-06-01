<?php
namespace backend\modules\dailyreport\controllers;
use yii;
use yii\db\Query;
use yii\helpers\Html;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\modules\user\models\Role;
use backend\modules\user\models\Employee;
use backend\modules\leave\models\Employeeleave;
use backend\modules\dailyreport\models\Dailyreport;
use backend\modules\user\controllers\UserController;
class DailyreportController extends \yii\web\Controller
{
    public function __construct($id, $module, $config = [])
    {
        $menus=Yii::$app->session['Menus'];
        $menusarray=(explode(",",$menus)); 
        parent::__construct($id, $module, $config);
        $flag= in_array( "dailyreport" ,$menusarray )?true:false;
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
                'only' => ['index', 'approvereport','savetask'],
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
        $query = new Query();
        $data=$query
            ->select(['E.DailyTargetTask as Target','E.LoginTime as Punchin','E.LogoutTime as Punchout', 'E.FullName as ReportBy', 'D.LoginLate','D.ExitFast','C.FullName as CreatedByName','V.FullName as VerifiedByName', 'D.*'])
            ->from('dailyreport D')
            ->leftJoin('employee E', 'D.UserID = E.EmployeeID')
            ->leftJoin('employee C','D.CreatedBy = C.EmployeeID')
            ->leftJoin('employee V','D.VerifiedBy = V.EmployeeID')
            ->orderBy(['D.Day'=>SORT_DESC])
            ->all();

        $model = new Dailyreport();
        $loggedInEmp = Yii::$app->session['UserID'];
        $stayTime = $this->stayTime($loggedInEmp);
        $flags = $this->checkLoginLateAndExitFast($loggedInEmp, $date=NULL);
        
        $query = new Query();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("select E.EmployeeID, E.FullName from employee E LEFT JOIN role R on E.RoleID = R.RoleID where R.Name = 'employee';");
        $employee = $command->queryAll();
        return $this->render("index",[
            'data' => $data,
            'staytime' => $stayTime,
            'flags' => $flags,
            'employee' => $employee,
            'model' => $model
        ]);        
    }
    public function actionSavetask(){
        $Role= UserController::CheckRole("dailyreport");
        if ($Role==TRUE){
            $Identity = Yii::$app->session['UserID'];
            $TotalTask = $_POST['totaltask'];
            $Report = $_POST['report'];
            $LoginLate = $_POST['loginlate'];
            $ExitFast = $_POST['exitfast'];
            $Day = Date("Y-m-d");
            $RDate=Date("Y-m-d");
            try {
                $model = Dailyreport::find()->where(['UserID'=>$Identity, 'Day' =>$Day])->one();
                if($model->IsSubmitted == 1){
                    return '{"result":false, "message":"Report of '.$Day.' Already Submitted"}';
                }
                $model->IsSubmitted="1";
                $model->IsPending="1";
                $model->TotalTask=$TotalTask;
                $model->Report=$Report;
                $model->ReportDate=$RDate;
                $model->ExitTime=$this->timeToSec(Date("H:i:s"));
                $model->ExitIP=$this->get_client_ip();
                $model->LoginLate=$LoginLate;
                $model->ExitFast=$ExitFast;
                $model->StayTime = $this->stayTimeHIS($Identity);
                if($model->save(false)){
                    Yii::$app->session->setFlash('ReportSubmitSuccess', "Report Submitted Succcessfully.");
                    return $this->redirect(Yii::$app->urlManager->baseUrl.'/dashboard');
                }
            } catch (Exception $e) {
                return "Caught Exception: ".$e;    
            }
        }
    }
    public function actionApprovereport(){
        $Role= UserController::CheckRole("dailyreport");
        $Identity = $_POST["ReportID"];
        if ($Role==TRUE && $Identity!=NUll && UserController::CheckUserAuthentication($Identity)){
            $Status = $_POST["Status"];
            $Remarks = $_POST["Remarks"];
            $model = Dailyreport::findOne($Identity);
            if($Status == 'true'){
                $model->IsAccepted = '1';
                $model->IsPending = '0';
                $model->VerifiedBy = Yii::$app->session['UserID'];
                $model->VerifiedDate = Date('Y-m-d H:i:s');   
            }else{
                $model->IsAccepted = '0';
                $model->IsPending = '0';
                $model->IsSubmitted = '0';
                $model->VerifiedBy = Yii::$app->session['UserID'];
                $model->Remarks = $Remarks;
            }
            $model->save(false);
            return $this->redirect(['dailyreport/index?tab=report']);
        }
    }
    public function actionAdddate(){
        $Role= UserController::CheckRole("dailyreport");
        $EmployeeID= $_POST["EmployeeID"];
        if ($Role==TRUE && $EmployeeID!=NUll && UserController::CheckUserAuthentication($EmployeeID)){
            $Date = $_POST["Date"];
            if($EmployeeID!=NULL && $Date !=NULL){
                try {
                    $query = new Query();
                    $connection = Yii::$app->getDb();
                    $qry = sprintf("SELECT `LoginTime` FROM `employee` WHERE `EmployeeID` = '%d'",$EmployeeID);
                    $result = $connection->createCommand($qry) /*->getRawSql()*/;
                    $res = $result->queryOne();
                    $model = Dailyreport::find()->where(['UserID'=>$EmployeeID, 'Day' => $Date])->all();
                    if(!empty($model)){
                        Yii::$app->session->setFlash('AttendanceAlreadyExist', "Attendance ALready Exist.");
                        return $this->redirect(['dailyreport/index?tab=addEmployeeReportDate']);
                    }
                    $AddDate = new Dailyreport();
                    $AddDate->UserID=$EmployeeID;
                    $AddDate->Day=$Date;
                    $AddDate->LoginTime=$res['LoginTime'];
                    $AddDate->LoginIP=$this->get_client_ip();
                    $AddDate->IsSubmitted='0';
                    $AddDate->IsPending='0';
                    $AddDate->IsPending='0';
                    $AddDate->HostName='0';
                    $AddDate->HostIP=$this->get_client_ip();
                    $AddDate->CreatedBy=yii::$app->session['EmployeeID'];
                    $AddDate->CreatedDate=Date('Y-m-d');
                    if ($AddDate->save(false)) {
                        Yii::$app->session->setFlash('AttendanceAddedFlash', "Attendance Added Succcessfully.");
                        return $this->redirect(['dailyreport/index?tab=addEmployeeReportDate']);
                    }
                } catch (Exception $e) {
                    return "Caught Exceptin:".$e;
                }
            }
        }
    }
    public function checkLoginLateAndExitFast($id, $date=NULL){
        ($date==NULL)?$date = Date('Y-m-d'):$date=$date;
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT `LoginTime`, `LogoutTime` FROM `employee` WHERE `EmployeeID` = '%d'",$id);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        $LoginTime = $this->timeToSec($res['LoginTime']);
        $LogoutTime = $this->timeToSec($res['LogoutTime']);
        $LoginTimeQuery = sprintf("SELECT LoginTime , ExitTime FROM `dailyreport` WHERE `UserID` = '%d' AND `Day` ='%s'",$id, $date);
        $LoginTimeQueryResult = $connection->createCommand($LoginTimeQuery) /*->getRawSql()*/;
        $LoginTimeQueryRes = $LoginTimeQueryResult->queryOne();
        $Now = $this->timeToSec(Date("H:i:s"));
        $LoginLateFlag = ($LoginTimeQueryRes['LoginTime'] > $LoginTime)?TRUE:"0";
        if(empty($LoginTimeQueryRes['ExitTime'])){
            $ExitFastFlag = ($Now < $LogoutTime)?TRUE:"0";
        }else{
            $ExitFastFlag = ($LoginTimeQueryRes['ExitTime'] < $LogoutTime)?TRUE:"0";
        }
        $lateBy = $this->getTime(abs($LoginTimeQueryRes['LoginTime'] - $LoginTime));
        $earlyBy = $this->getTime(abs($LogoutTime-$Now) );
        return '{"LoginFlag":'.$LoginLateFlag.', "ExitFlag":'.$ExitFastFlag.', "Late":"'.$lateBy.'", "Early":"'.$earlyBy.'"}';
    }
    public function actionReportsubmit(){
        $model = new \backend\modules\dailyreport\models\Dailyreport();
        $loggedInEmp1 = Yii::$app->session['UserID'];
        if ($model->load(Yii::$app->request->post())) {
            $connection = Yii::$app->getDb();
            $qry = sprintf("SELECT `LoginTime`, `LogoutTime` FROM `employee` WHERE `EmployeeID` = '%d'",$loggedInEmp1);
            $result = $connection->createCommand($qry) /*->getRawSql()*/;
            $res = $result->queryOne();
            $LoginTime = $this->timeToSec($res['LoginTime']);
            $LogoutTime = $this->timeToSec($res['LogoutTime']);
            $qry1 = sprintf("SELECT LoginTime FROM `dailyreport` WHERE `UserID` = '%d' AND `IsSubmitted` = '0' AND `Day` = '%s'",$loggedInEmp1, $model->Day);
            $result1 = $connection->createCommand($qry1) /*->getRawSql()*/;
            $res1 = $result1->queryOne();
            $StayTime = abs($res1['LoginTime'] - $LogoutTime);
            $model1 = Dailyreport::find()->where(['UserID' => $loggedInEmp1, 'Day' => $model->Day])->one();
            $model1->UserID = $loggedInEmp1;
            $model1->Day = $model->Day;
            $model1->ReportDate = $model->Day;;
            $model1->LoginTime = $LoginTime;
            $model1->ExitTime = $LogoutTime;
            $model1->ExitIP = $this->get_client_ip();
            $model1->LoginIP = $this->get_client_ip();
            $model1->HostIP = $this->get_client_ip();
            $model1->Report = $model->Report;
            $model1->LoginLate = $model->LoginLate;
            $model1->ExitFast = $model->ExitFast;
            $model1->TotalTask = $model->TotalTask;
            $model1->HostName = gethostname();
            $model1->IsSubmitted = '1';
            $model1->IsPending = '1';
            $model1->StayTime = $StayTime;
            if($model1->save(false)){
                Yii::$app->session->setFlash('ReportSubmitSuccess', "Report Submitted Succcessfully.");
                return $this->redirect(Yii::$app->urlManager->baseUrl.'/dashboard');
            }
        }
        $loggedInEmp = Yii::$app->session['UserID'];
        $date = $_GET['day'];
        $flags = $this->checkLoginLateAndExitFast($loggedInEmp, $date);        
        return $this->render('reportsubmit',[
            'model' => $model,
            'flags' => $flags
        ]);
    }
    public function stayTime($id){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT LoginTime FROM `dailyreport` WHERE `UserID` = '%d' AND `IsSubmitted` = '0' AND `Day` = CURDATE()",$id);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        if(empty($res)){
            $qry1 = sprintf("SELECT StayTime FROM `dailyreport` WHERE `UserID` = '%d' AND `IsSubmitted` = '1' AND `Day` = CURDATE()",$id);
            $result1 = $connection->createCommand($qry1) /*->getRawSql()*/;
            $res1 = $result1->queryOne();
            $youstay1 = $this->getTime($res1['StayTime']);
            return $youstay1."(Report Submitted)";
        }else{    
            $now = $this->timeToSec(Date("H:i:s"));
            $youstay = $this->getTime($now-$res['LoginTime']);
            return $youstay;
        }
    }
    public function stayTimeHIS($id){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT LoginTime FROM `dailyreport` WHERE `UserID` = '%d' AND `IsSubmitted` = '0' AND `Day` = CURDATE()",$id);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        $now = $this->timeToSec(Date("H:i:s"));
        $youstay = $now-$res['LoginTime'];
        return $youstay;
    }
    function timeToSec($string){
        list($hour, $min, $sec) =array_pad(explode(':', $string, 3), -3, NULL);
        return $hour*3600+$min*60+$sec;
    }
    function getTime($duration) {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        return "$hours Hours $minutes Minutes";
    }
    function getHMS($duration) {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        return "$hours:$minutes";
    }
    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
            $ipaddress = 'UNKNOWN';
            return $ipaddress;
            }
    public function actionGetreport(){
        $Role= UserController::CheckRole("dailyreport");
        if ($Role==TRUE){
            $EmployeeID= $_POST["EmployeeID"];
            $From = $_POST["From"];
            $To = $_POST["To"];
            $html = "";
            try {
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = sprintf("SELECT `E`.`FullName`, `D`.* FROM `dailyreport` D LEFT JOIN `employee` E ON D.`UserID` = E.`UserID` where D.`UserID` = '%d' AND D.`IsSubmitted` = '1' AND `Day` BETWEEN '%s' AND '%s' ORDER BY D.`Day` DESC;",$EmployeeID, $From, $To);
                $result = $connection->createCommand($qry) /*->getRawSql()*/;
                $res = $result->queryAll();
                if (empty($res)) {
                    $html .= '<tr><td colspan="6" align="center"><strong>No Data Available.</strong></td></tr>';
                }else{
                    foreach ($res as $key => $R) {
                        $html .= '<tr>';
                        $html .= '<td>'.$R['Day']."<br/>".date("l",strtotime($R['Day'])).'</td>';
/*
    *check if login late and exit fast and indicate login late and exit fast of employee
*/
                        $flag_L_E = $this->checkLoginLateAndExitFast($EmployeeID, $R['Day']);
                        $jsonFlag = json_decode($flag_L_E, true);
                        $classlogin = ($jsonFlag['LoginFlag'] == 1)?'danger':'';
                        $classExit = ($jsonFlag['ExitFlag'] == 1)?'danger':'';
                        $html .= '<td login-fast='.$R['Day'].' class='.$classlogin.'><strong>Time: </strong>'.date('h:i A', strtotime($this->getHMS($R['LoginTime'])))."<br/><strong>IP: </strong>".$R['LoginIP'].'</td>';
                        $html .= '<td class='.$classExit.'><strong>Time: </strong>'.date('h:i A', strtotime($this->getHMS($R['ExitTime'])))."<br/><strong>IP: </strong>".$R['ExitIP'].'</td>';
                        $html .= '<td>'.$this->getTime($R['StayTime']).'</td>';
                        $html .= '<td>'.$R['Report'].'</td>';
                        $html .= '<td>'.$R['TotalTask'].'</td>';
                        $html .= '</tr>';
                    }
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['html' => $html];               
            } catch (Exception $e) {
                return $e;
            }
        }
    }
/*
    *For Employee Attendance And Daily report Reporting
*/
    public function actionReport(){
        $model = new Dailyreport();
        $connection = Yii::$app->getDb();
        $qry1 = sprintf("SELECT DISTINCT(UserID) as `User` FROM `dailyreport`");
        $result1 = $connection->createCommand($qry1) /*->getRawSql()*/;
        $res1 = $result1->queryAll();
        $td = " ";
        foreach($res1 as $result){
            $td .= $this->getInformation($result['User']);
            if($td == " "){
                $td = '<tr><td align="center" colspan="5">No Data Available.</td></tr>';
            }
        }
        return $this->render("report",[
            'model' => $model,
            'td' => $td
        ]);    
    }
    public function getInformation($id){
        try {
            $quer = new Query();
            $query = $quer->select(['E.FullName', 'D.*'])->where(['D.UserID'=> $id, 'D.IsSubmitted' => 1,'D.IsAccepted' => 1,])->andFilterWhere(['BETWEEN', 'Day',date('Y-m-01'), date('Y-m-d')])->from('dailyreport D')->leftJoin('employee E','D.UserID = E.UserID')->orderBy(['D.Day'=>SORT_DESC]);
            $command = $query->createCommand()/*->getRawSql()*/;
            $data = $command->queryAll();
            if (!empty($data)) {
                $html = "";
                $sumWorkingHours = 0;
                $sumTotalTask = 0;
                $TotalWorkingDays = 0;
                $TargetTask = 0;
                $From = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-01"))) . "-1 month"));
                $To = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-t"))) . "-1 month"));
                foreach ($data as $key => $dat) {
                    $UserID = $dat['UserID'];
                    $Name = $dat['FullName'];
                    $sumWorkingHours += $dat['StayTime'];
                    $sumTotalTask += $dat['TotalTask'];
                    $TotalWorkingDays++;
                }
                $html .= '<tr>';
                $html .= '<td>'. Html::a($Name, ['dailyreport/singlepageinformation?id='.$UserID.'&from='.$From.'&to='.$To]) .'</td>';
                $html .= '<td>'.$TotalWorkingDays.'</td>';
                $html .= '<td>'.$this->getTime($sumWorkingHours).'</td>';
                $html .= '<td>'.$sumTotalTask.'</td>';
                $html .= '<td>0</td>';
                $html .= '</tr>';
                return $html;
            }else{
                return FALSE;
            }
        } catch (Exception $e) {
            return $e;
        }
    }
    public function actionGetsinglereport(){
        $Role= UserController::CheckRole("dailyreport");
        if ($Role==TRUE){
            $EmployeeID = $_POST['EmployeeID'];
            $Range = $_POST['Range'];
            $Explode = explode('to',$Range);
            $From = $Explode[0];
            $To = $Explode[1];
            try {
                $connection = Yii::$app->getDb();
                $qry1 = sprintf("SELECT E.`FullName`, D.* FROM `dailyreport` D LEFT JOIN employee E ON D.`UserID` = E.`UserID` WHERE D.`UserID` = '%d' and D.`Day` BETWEEN '%s' AND '%s';",$EmployeeID, $From, $To);
                $result1 = $connection->createCommand($qry1) /*->getRawSql()*/;
                $res1 = $result1->queryAll();
                if (!empty($res1)) {
                    $html = "";
                    $sumWorkingHours = 0;
                    $sumTotalTask = 0;
                    $TotalWorkingDays = 0;
                    $TargetTask = 0;
                    foreach ($res1 as $key => $dat) {
                        $UserID = $dat['UserID'];
                        $Name = $dat['FullName'];
                        $sumWorkingHours += $dat['StayTime'];
                        $sumTotalTask += $dat['TotalTask'];
                        $TotalWorkingDays++;
                    }
                    $html .= '<tr>';
                    $html .= '<td>'. Html::a($Name, ['dailyreport/singlepageinformation?id='.$UserID.'&from='.$From.'&to='.$To]) .'</td>';
                    $html .= '<td>'.$TotalWorkingDays.'</td>';
                    $html .= '<td>'.$this->getTime($sumWorkingHours).'</td>';
                    $html .= '<td>'.$sumTotalTask.'</td>';
                    $html .= '<td>0</td>';
                    $html .= '</tr>';
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'html' => $html,
                        'result' => 'TRUE',
                        'message' => 'Data Loaded Successfully'
                    ];
                }else{
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'result' => 'FALSE',
                        'message' => 'Data Not Found.'
                    ];
                }
            } catch (Exception $e) {
                return 'Caught Exception: '.$e;
            }
        }
    }
    public function actionSinglepageinformation($id, $from, $to){
        $quer = new Query();
        $query = $quer->select(['D.*', 'E.FullName'])->where(['D.UserID'=> $id, 'D.IsSubmitted' => 1,'D.IsAccepted' => 1,])->andFilterWhere(['BETWEEN', 'Day',$from, $to])->from('dailyreport D')->leftJoin('employee E','D.UserID = E.UserID')->orderBy(['D.Day'=>SORT_DESC]);
        $command = $query->createCommand()/*->getRawSql()*/;
        $model = $command->queryAll();
        if(empty($model)){
            return $this->render("employeereport",[
                'LoginIPCount' => 0,
                'ExitIPCount' => 0,
                'Name' => $this->getEmployeeName($id),
                'from' => 0,
                'to' => 0,
                'LoginLate' => 0,
                'ExitFast' => 0,
                'WorkingHour' => 0,
                'Attendance' => 0,
                'TotalTaskDone' => 0,
                'TotalDaysOfThisMonth' => 0
            ]); 
        }else{            
            $date1 = date_create( date("Y-m-d", strtotime('-1 day', strtotime($from))));
            $date2=date_create(date("Y-m-d", strtotime($to)));
            $diff=date_diff($date1,$date2);
            $TotalDaysOfThisMonth = intval($diff->format("%a"));
            $Attendance = 0;
            $WorkingHour = 0;
            $TotalTaskDone = 0;
            $LoginLate = 0;
            $ExitFast = 0;
            foreach ($model as $key => $mo) {
                $Name = $mo['FullName'];
                $WorkingHour += $mo['StayTime'];
                $TotalTaskDone += $mo['TotalTask'];
                $Attendance ++;
/*
    *Getting login Late and Exit fast Days
*/
                $Dat = $this->checkLoginLateAndExitFast($id, $mo['Day']);
                $jsonFlag = json_decode($Dat, true);
                ($jsonFlag['LoginFlag'] == 1)?$LoginLate++:$LoginLate;       
                ($jsonFlag['ExitFlag'] == 1)?$ExitFast++:$ExitFast; 
            }
/*
    *Finding the Login IP count and Exit IP count
*/
            $IPS = $this->getLoginIpCount($id, date('Y-m-01'), date('Y-m-d'));
            $jsonIP = json_decode($IPS, true);
            $LoginIPCount = $jsonIP['LoginIP'];
            $ExitIPCount = $jsonIP['ExitIP'];
            return $this->render("employeereport",[
                'model' => $model,
                'LoginIPCount' => $LoginIPCount,
                'ExitIPCount' => $ExitIPCount,
                'Name' => $Name,
                'from' => $from,
                'to' => $to,
                'LoginLate' => $LoginLate,
                'ExitFast' => $ExitFast,
                'WorkingHour' => $WorkingHour,
                'Attendance' => $Attendance,
                'TotalTaskDone' => $TotalTaskDone,
                'TotalDaysOfThisMonth' => $TotalDaysOfThisMonth
            ]); 
        }
    }
    public function getLoginIpCount($id, $from, $to){
        try {
            $connection = Yii::$app->getDb();
            $qry1 = sprintf("SELECT COUNT(DISTINCT(`LoginIP`)) as IPCountLogin, COUNT(DISTINCT(`ExitIP`)) as IPCountExit FROM `dailyreport` WHERE `UserID` = '%d' AND Day BETWEEN '%s' AND '%s';",$id, $from, $to);
            $result1 = $connection->createCommand($qry1) /*->getRawSql()*/;
            $res1 = $result1->queryOne();
            return '{"LoginIP":'.$res1['IPCountLogin'].', "ExitIP":'.$res1['IPCountExit'].'}';
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getEmployeeName($id){
        $connection = Yii::$app->getDb();
            $qry1 = sprintf("SELECT FullName FROM `employee` WHERE EmployeeID = '%d' AND IsActive = 1 AND IsTerminated = 0;",$id);
            $result1 = $connection->createCommand($qry1) /*->getRawSql()*/;
            $res1 = $result1->queryOne();
            return $res1['FullName'];
    }
}
