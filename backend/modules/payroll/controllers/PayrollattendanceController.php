<?php
namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Payrollattendance;
use backend\modules\user\controllers\UserController;
class PayrollattendanceController extends \yii\web\Controller
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
        $model = new Payrollattendance();
        return $this->render('index',[
            'model' => $model
        ]);
    }
    public function actionSavesettings(){
        $Role =UserController::CheckRole("payroll");
        if ($Role == true) {
            try {
                $Name = $_POST["Name"];
                $Month = $_POST["Month"];
                $Year = $_POST["Year"];
                $AttendanceDays = $_POST["AttendanceDays"];
                $IsNewRecord = $_POST['IsNewRecord'];
                if ($IsNewRecord!=NULL && $Name!=NULL && $Month != NULL && $Year != NULL && $AttendanceDays != NULL) {
                    if($IsNewRecord == 0){
                        $model = new Payrollattendance();
                        $model->CreatedDate = date('Y-m-d');
                        $model->CreatedBy =Yii::$app->session['UserID'];
                        $saveMsg =  '{"month":'.$Month.', "year":'.$Year.', "result":true,"message":"Saved Successfully"}';             
                    }else{
                        $model = Payrollattendance::findOne($IsNewRecord);
                        $model->UpdatedDate = date('Y-m-d');
                        $model->UpdatedBy =Yii::$app->session['UserID'];
                        $saveMsg =  '{"month":'.$Month.', "year":'.$Year.', "result":true,"message":"Updated Successfully"}';
                    }   
                    $model->EmployeeID = $Name;
                    $model->Month = $Month;
                    $model->Year = $Year;
                    $model->AttendanceDays = $AttendanceDays;
                    $return = ($model->save() == TRUE)?$saveMsg:'{"result":false,"message":"Not Saved"}';
                    return $return;
                }     
            } catch (Exception $e) {  
                return "Caught Exception:".$e;
            } 
        }
    }
    public function actionGetsetting()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                $date = date('Y-m-d');
                $newdate = strtotime('-1 month', strtotime($date));
                $sentYear = date('Y', $newdate);
                $sentMonth = date('n', $newdate);
                $yearTo = $_POST['year'];
                $monthTo = $_POST['month'];
                if ($yearTo == NULL && $monthTo == NULL) {
                    $yearTo = $sentYear;
                    $monthTo = $sentMonth;
                }
                $connection = Yii::$app->getDb();
                $querySettings = sprintf("SELECT `PA`.`IsActive`,`PA`.`PayrollAttendanceID`, `PA`.`EmployeeID`,`PA`.`Month`,`PA`.`Year`,`PA`.`AttendanceDays`, `E`.`FullName` FROM `payrollattendance` `PA` LEFT JOIN `employee` `E` ON `PA`.`EmployeeID` = `E`.`EmployeeID` WHERE`PA`.`Month`='%d' AND `PA`.`Year`='%d' ORDER BY `PA`.`IsActive` DESC,  `PA`.`CreatedDate` DESC",$monthTo ,$yearTo);
                $settings = $connection->createCommand($querySettings)/*->getRawSql()*/;
                // print_r($settings); die();
                $sets = $settings->queryAll();
                $html = "";
                if (sizeof($sets) < 1) {
                    $html .= "<tr><td align='center' colspan='5'>No Data Available</td></td>";
                } else {        
                    foreach($sets as $key => $set)
                    {
                        $active = '<td><span class="hand edit" data-id="' . $set["PayrollAttendanceID"].'">edit&nbsp;&nbsp;&nbsp;</span><span class="hand deactivate" data-id="' . $set["PayrollAttendanceID"].'">deactivate</span></td>';
                        $inactive = '<td>Deactivated</td>';
                        $html.= "<tr>";
                        $html.= "<td emp-id=".$set['EmployeeID'].">" . $set['FullName'] . "</td>";
                        $html.= "<td attr-month=".$set['Month'].">" . date("F", mktime(0, 0, 0, $set['Month'], 10)). "</td>";
                        $html.= "<td>" . $set['Year'] . "</td>";
                        $html.= "<td>" . $set['AttendanceDays'] . "</td>";
                        $html.= $set['IsActive'] == 1?$active:$inactive;
                        $html.= "</tr>"; 
                    }
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['html' => $html,'month' => $monthTo];
            }
            catch(Exception $e)
            {
                return $e;
            }
        }
    }
    public function actionDeactivate()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role == true)
        {
            try
            {
                $ID = $_POST['ID'];
                if($ID != NULL)
                {
                    $query = new Query();
                    $connection = Yii::$app->getDb();
                    $qry = sprintf("UPDATE `payrollattendance` SET `IsActive` = '0' WHERE `payrollattendance`.`PayrollAttendanceID` = %d;", $ID);
                    $result = $connection->createCommand($qry)/* ->getRawSql()*/;
                    $res = $result->execute();
                    $return = $res == true ? '{"result":true,"message":"Deactivated Successfully"}' : '{"result":false,"message":"Deactivation Failed"}';
                    return $return;
                }
            }
            catch(Exception $e)
            {
                return $e;
            }
        }
    }
}
