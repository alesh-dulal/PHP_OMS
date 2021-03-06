<?php
namespace backend\modules\dashboard\controllers;
use Yii;
use yii\db\Query;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\modules\user\models\Employee;
use backend\modules\holiday\models\Holiday;
use backend\modules\dashboard\models\Poststatus;
use backend\modules\attendance\models\Attendance;
use backend\modules\dailyreport\models\Dailyreport;
use backend\modules\user\controllers\UserController;
/*
    * Default controller for the `dashboard` module
*/
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'savepost'],
                'rules' => [
                     /*allow authenticated users*/
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    /*everything else is denied*/
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
    /*
        * Renders the index view for the module
        * @return string
    */
    public function actionIndex()
    {
        $model=new Poststatus();
        $SelectEmployee = Employee::find()->where(['IsActive'=>1])->all();
        $Employee = (count($SelectEmployee) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectEmployee, 'EmployeeID', 'FullName');
        $PostStatus = Poststatus::find()->orderBy(['InsertedDate'=>SORT_DESC])->limit(5)->all();
        $Holiday= Holiday::find()->all();
        $BornToday = Employee::find()->select('FullName')->where(['IsActive'=>1])->andWhere(['DATE_FORMAT(`DOB`,"%m-%d")'=>Date('m-d')])->all();
        $query = new Query();      
        $connection = Yii::$app->getDb();
        $rol=strtolower(Yii::$app->session['Role']);
        $whereCondition=" AND E.Supervisor=".Yii::$app->session['EmployeeID'];
        $command = $connection->createCommand("select E.DepartmentID, E.FullName,A.Attendance,CASE when A.Attendance is null then 'Absend' else 'Present' end Status from employee E left join (SELECT A.UserID,min(time(A.AttendanceDate))Attendance FROM  attlog A where Date(A.AttendanceDate) BETWEEN date(CURRENT_DATE()) and date(CURRENT_DATE()) group by A.UserID)A on A.UserID=E.BiometricID where IsActive = 1 ".(($rol=='supervisor')?$whereCondition:"")
                                             );
        $EmployeeTodayAttn = $command->queryAll();
       /* 
            *end employee attendance status
            *Report left to be verified
            *only show in the case of supervisor and HR
        */
        $CountSubmittedReport =count(Dailyreport::find()->Where(['IsPending'=>0])->all());
        $events=[];
        foreach ($Holiday AS $holiday){
            $Event = new \yii2fullcalendar\models\Event();
            $Event->title = $holiday->Name;
            $Event->start = $holiday->Day;
            $Event->backgroundColor = 'red';
            $events[] = $Event;
        }
        $query = new Query();
        $EmployeeLeave = $query->select(['EM.FullName as Name','E.From','E.To'])->from('employeeleave E')->where(['IsApproved'=>1])->leftJoin('employee EM','E.EmployeeID = EM.EmployeeID')->all();
        $query1 = new Query();
        $LeaveToday = $query1->select(['Ei.FullName as Name','El.From','El.To'])->from('employeeleave El')->where(['IsApproved'=>1])->andWhere(['From'=>date('Y-m-d')])->leftJoin('employee Ei','El.EmployeeID = Ei.EmployeeID')->all();
        $queryX = new Query();      
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("select ListItemID, Title  FROM `listitems` WHERE Type= 'department'");
        $departmentLists = $command->queryAll();
        foreach($EmployeeLeave as $employee){
            $Event = new \yii2fullcalendar\models\Event();
            $Event->title = $employee['Name'];
            $Event->start = $employee['From'];
            $Event->end = $employee['To'];
            $events[] = $Event;
        }
        $ID = Yii::$app->session['UserID'];
        $checkForUnsubmitted = $this->checkForUnsubmittedReport($ID);
        $json = json_decode($checkForUnsubmitted, true);
        if($json['results'] == true){
            $URL = '/dailyreport/dailyreport/reportsubmit?day='.$json['date'];
            Yii::$app->session->setFlash('ForgotReportSubmission', "<Strong>You Have Not Submitted Report Of ".date('F j, Y',strtotime($json['date']))."</strong>.".Html::a('<span>SubmitNow</span>',[$URL],['class'=>'btn btn-danger btn-xs pull-right']).".");
        }
        $BirthdayFlag = self::CheckIfBirthday($ID);
        $jsonBirthday = json_decode($BirthdayFlag, true);
        if($jsonBirthday['result'] == TRUE){
            Yii::$app->session->setFlash('BirthdayFlash', "<strong>Happy Birthday ".$jsonBirthday['name'].".</strong>");
        }
        return $this->render('index',[
            'Employee' => $Employee,
            'PostStatus'=>$PostStatus,
            'model'=>$model,
            'events'=>$events,
            'departmentLists'=>$departmentLists,
            'LeaveToday'=>$LeaveToday,
            'BornToday'=>$BornToday,
            'EmployeeTodayAttn'=>$EmployeeTodayAttn,
            'CountSubmittedReport'=>$CountSubmittedReport
        ]);
    }
    public function actionSavepost(){
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $Posts= new Poststatus();
        $Posts->Title=$title;
        $Posts->Description=$description;
        $Posts->InsertedBy=Yii::$app->user->id;
        $Posts->Type=$type;
        $Posts->save();
        return $this->redirect('/oms/dashboard/default');
    }
    public function actionTodaylog(){
        $queryS = "select time(min(AttendanceDate))CheckIN from attlog where date(AttendanceDate) BETWEEN date(now()) and date(now())  and UserID = (select BiometricID from employee where  EmployeeID = '%d')";
        $queryQ = sprintf($queryS, Yii::$app->session['UserID']);
        $connection = Yii::$app->getDb();
        $queryCheck = $connection->createCommand($queryQ);
        $PunchinTime = $queryCheck->queryAll();
        if ($PunchinTime[0]['CheckIN'] == Null) {
            return "";
        } else {
            return $PunchinTime[0]['CheckIN'];
        }
    }
    public function checkForUnsubmittedReport($id){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT * FROM `dailyreport` WHERE `UserID` = '%d' AND `IsSubmitted`='0' AND `Day` < CURDATE()",$id);
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $res = $result->queryOne();
        if(!empty($res)){
            return '{"results":true,"date":"'.$res['Day'].'"}';
        }else{
            return '{"results":false}';
        }
    }
    public function CheckIfBirthday($id){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $result = $connection->createCommand('SELECT FullName, STRCMP(DATE_FORMAT(`DOB`,"%m-%d"), DATE_FORMAT(CURDATE(),"%m-%d")) AS `IsBirthday` FROM `employee` WHERE `EmployeeID`='.$id.';') /*->getRawSql()*/;
        $res = $result->queryOne();
        $return = ($res['IsBirthday'] == 0)?'{"result":true, "name":"'.$res['FullName'].'"}':'{"result":false, "name":"'.$res['FullName'].'"}';
        return $return;
    }
}
