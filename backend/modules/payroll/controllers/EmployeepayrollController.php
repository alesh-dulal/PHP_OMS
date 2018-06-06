<?php
namespace backend\modules\payroll\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Employeepayroll;
use backend\modules\user\controllers\UserController;
class EmployeepayrollController extends \yii\web\Controller
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
        $model = new Employeepayroll();
        return $this->render('index',[
            'model'=>$model,
        ]);
    }    

    public function actionEmpsalary()
    {
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            try {
                $employeeID = $_POST['employeeID'];
                $query = new Query();
                $calc = $query->select(['Salary as BasicSalary'])->from('employee E')->where(['E.IsActive'=>1,'EmployeeID'=>$employeeID])->one();
                if ($calc !=NULL) {
                    $response = Yii::$app->response;
                    $response->format = \yii\web\Response::FORMAT_JSON;
                    $response->data = $calc;
                    return $response;
                }
            } catch (Exception $e) {
                return $e;    
            }
        }
    } 

    public function actionAllowancelist(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            try {
                $query = new Query();
                $allowances = $query->select(['PayrollSettingID', 'IsAllowance','Title','Amount','Formula'])->from('payrollsetting')->where(['IsActive'=>1])->all();
                $allow=NULL;
                $dedu=NULL;
                if($allowances != NULL && sizeof($allowances) > 0){
                    foreach ($allowances as $allowance){
                        if($allowance['IsAllowance'] == 0){
                            $allow .='<tr data-type="'.$allowance["IsAllowance"].'"data-id="'.$allowance["PayrollSettingID"] .'">';
                            $allow.='<td>'.$allowance['Title'].'</td>';
                            if ($allowance['Formula'] != NULL) {
                                $allow.='<td>'.$allowance['Formula'].'</td>';
                            }else{
                                $allow.='<td class="editable" contenteditable ="true">'.$allowance["Amount"].'</td>';
                            }
                            $allow.='<td hidden="true">'.$allowance['Formula'].'</td>';
                            $allow .='</tr>';
                        }else{
                            $dedu .='<tr data-type="'.$allowance["IsAllowance"].'"data-id="'.$allowance["PayrollSettingID"] .'">';
                            $dedu.='<td>'.$allowance['Title'].'</td>';
                            if ($allowance['Formula'] != NULL) {
                                $dedu.='<td>'.$allowance['Formula'].'</td>';
                            }else{
                                $dedu.='<td class="editable" contenteditable ="true">'.$allowance["Amount"].'</td>';
                            }
                            $dedu.='<td hidden="true">'.$allowance['Formula'].'</td>';
                            $dedu .='</tr>';
                        }
                    }
                }
            } catch (Exception $e) {
                return $e;
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'allowance' => $allow ,
                'deduction' => $dedu
            ];
        }
    }

    public function actionCalculate()
    {
        $Role = UserController::CheckRole("payroll");
        if ($Role === true)
        {
            try
            {
                $EmployeeID = $_POST['employeeID'];
                $AllowanceID = $_POST['AllowanceID'];
                if ($EmployeeID != NULL && $AllowanceID != NULL)
                {
                    $deleteExisting = $this->deleteBeforeSave($EmployeeID);
                    if ($deleteExisting == true)
                    {
                        $i = 0;
                        $insert = '';
                        $loggedInUserID = Yii::$app->session['UserID'];
                        foreach($AllowanceID as $key => $Allowance)
                        {
                            if ($i != 0) $insert.= ',';
                            $insert.= '(';
                            $insert.= $EmployeeID . ",";
                            $insert.= $Allowance['ID'] . ",";
                            $insert.= $Allowance['Type'] . ",";
                            $insert.= "'" . $Allowance['Name'] . "',";
                            $insert.= $Allowance['Value'] . ",";
                            $insert.= "'" . Date('Y-m-d') . "',";
                            $insert.= $loggedInUserID;
                            $insert.= ')';
                            $i++;
                        }
                        $query = new Query();
                        $connection = Yii::$app->getDb();
                        $qry = sprintf("INSERT INTO `employeepayroll` (`EmployeeID`, `AllowanceID`, `IsAllowance`, `AllowanceTitle`, `AllowanceAmount`, `CreatedDate`, `CreatedBy`) VALUES %s;", $insert);
                        $result = $connection->createCommand($qry) /*->getRawSql()*/;
                        $res = $result->execute();
                        $return = $res == TRUE ? '{"result":true,"message":"Saved successfully"}' : '{"result":false,"message":"Not Saved successfully"}';
                        return $return;
                    }
                    else
                    {
                        echo "Awesome Error.";
                    }
                }
            }
            catch(Exception $e)
            {
                return $e;
            }
        }
    }

    public function deleteBeforeSave($id){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT * FROM `employeepayroll` WHERE EmployeeID =".$id.";");
        $Rows = $command->queryAll();
        if(sizeof($Rows) == 0)
        {
            return TRUE;
        }
        else
        {
            $qry= sprintf("DELETE FROM `employeepayroll` WHERE EmployeeID = %d",$id);
            $result=$connection->createCommand($qry)/*->getRawSql()*/;
            $res=$result->execute();
            $return = $res == TRUE ? true:false;
            return $return;	
        }
    }

    public function actionEmployeelistfordeny(){
        $Role = UserController::CheckRole("payroll");
        if ($Role === true)
        {
            try {
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = "SELECT `EmployeeID`, `FullName`,`BankAccountNumber`,`Email`, `Salary` from `employee` WHERE EmployeeID NOT IN (SELECT MIN(EmployeeID) FROM employee) AND EmployeeID NOT IN (SELECT EmployeeID FROM employeepayrolldeny WHERE IsActive = 1) AND IsActive = 1";
                $result = $connection->createCommand($qry) /*->getRawSql()*/;
                $res = $result->queryAll();
                $html = "";
                foreach ($res as $key => $r) {
                    $html .= "<tr>";
                    $html .= "<td><input type='checkbox' attr-id = ".$r['EmployeeID']." class='deny-this' id='checkDenyThis'></td><td>".$r['FullName']."</td>";
                    $html .= "</tr>";
                }
                return '{"result":true,"message":"'.$html.'"}';  
            } catch (Exception $e) {
                return "Caught exception:".$e.".";
            }
        }
    }

    public function actionDeniedlist(){
        $Role = UserController::CheckRole("payroll");
        if ($Role === true)
        {
            try {
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = "SELECT ED.EmployeeID, E.FullName FROM employeepayrolldeny ED LEFT JOIN employee E ON E.EmployeeID = ED.EmployeeID WHERE ED.IsActive = 1";
                $result = $connection->createCommand($qry) /*->getRawSql()*/;
                $res = $result->queryAll();
                $html = "";
                if (sizeof($res) == 0) {
                    $html .= "<li class='list-group-item'style='background:red; text-align:center;'>No Employee Denied Yet.</li>";
                }else{
                    foreach ($res as $key => $r) {
                        $html .= "<li class='list-group-item'>".$r['FullName']."<input attr-empid='".$r['EmployeeID']."' type='checkbox' style='float:right;' name='undenythis' class='undeny-this'></li>";
                    }
                }
                return '{"result":true,"message":"'.$html.'"}';  
            } catch (Exception $e) {
                return "Caught exception:".$e.".";
            }
        }
    }

    public function actionDenypayroll(){
        $Role = UserController::CheckRole("payroll");
        if ($Role === true)
        {
            try {
                $array = $_POST['array'];
                $i = 0;
                $insert = '';
                $loggedInUserID = YII::$app->session['EmployeeID'];
                if(sizeof($array) > 0)
                {
                    foreach ($array as $key => $arr) {
                        if ($i != 0) 
                            $insert .= ',';
                        $insert .= '(';
                        $insert .= "'" . $arr['EmployeeID'] . "',";
                        $insert .= "'" . $loggedInUserID . "'";
                        $insert .= ')';
                        $i++;
                    }
                    $query = new Query();
                    $connection = Yii::$app->getDb();
                    $qry = sprintf("INSERT INTO `employeepayrolldeny` (`EmployeeID`,`CreatedBy`) VALUES %s;", $insert);
                    $result = $connection->createCommand($qry) /*->getRawSql()*/;
                    $res = $result->execute();
                    $return = $res == true ? '{"result":true,"message":"Denied successfully"}' : '{"result":false,"message":"Denianl Unsuccess."}';
                    return $return;
                }
            } catch (Exception $e) {
                return "Caught exception:".$e.".";
            }
        }
    }

    public function actionUndenialpayroll()
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
                    $qry = sprintf("UPDATE `employeepayrolldeny` SET `IsActive`= '0' WHERE EmployeeID IN (%s) and IsActive=1", $update);
                    $result = $connection->createCommand($qry)/* ->getRawSql()*/;
                    // print_r($result); die();
                    $res = $result->execute();
                    $return = $res == true ? '{"result":true,"message":"Undenied Successfully"}' : '{"result":false,"message":"Denail Failed"}';
                    return $return;
                }
                catch(Exception $e){
                    return $e;
                }
            }
        }
    }
}
