<?php

namespace backend\modules\leave\controllers;

use Yii;
use yii\db\Query;
use yii\web\Session;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\modules\user\models\Role;
use backend\modules\leave\models\Leave;
use backend\modules\user\models\Employee;
use backend\modules\user\models\Listitems;
use backend\modules\leave\models\Employeeleave;
use backend\modules\user\controllers\UserController;
use backend\modules\leave\models\Employeeleavedetail;

class LeaveController extends \yii\web\Controller
{
     public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             $flag= in_array( "leave" ,$menusarray)?true:false;
             parent::__construct($id, $module, $config);
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
                'only' => ['index', 'approve'],
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
    	$LeaveModel = new Leave();
    	$EmployeeLeaveModel = new Employeeleave();

    	if($EmployeeLeaveModel->load(Yii::$app->request->post())) {
            $EmployeeLeaveModel->CreatedBy = Yii::$app->session['UserID'];
        	$EmployeeLeaveModel->Year = Date('Y');
        $file = UploadedFile::getInstance($EmployeeLeaveModel,'File');
            if ($file){
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $filename = "_Leave_".Yii::$app->user->identity->UserName."_".Date("Y-m-d") . "." . $ext;
                $path = Yii::$app->basePath . "/../uploads";
                
                $file->saveAs($path . "/" . $filename);
                $pathForDatabase = explode("..", $path);
            $EmployeeLeaveModel->File = $pathForDatabase[1]. "/" . $filename;    
            }
            // check for the employee leave balance availability 
            //if no of days request for particular leavetype is greater then the available balance then the leave request wont be created.

            // echo "<pre>"; print_r($EmployeeLeaveModel); die();

        $query5 = new Query();
        $AvailableDays = $query5->select(['LeaveTypeID', 'Balance'])->where(['EmployeeID'=>$EmployeeLeaveModel['EmployeeID']])->andWhere(['LeaveID'=>$EmployeeLeaveModel['LeaveID']])->from('leave')->one();

            $EmployeeLeaveModel->LeaveTypeID = $AvailableDays['LeaveTypeID'];

            if ($EmployeeLeaveModel['NoOfDays'] <= $AvailableDays['Balance']) {

                if ($EmployeeLeaveModel->EmployeeID == Yii::$app->session['EmployeeID']) {

                   $EmployeeLeaveModel->save();
                   Yii::$app->session->setFlash('leaverequested', "Leave Requested Successfully. ");
                return $this->redirect('index');

                } else {
                    $EmployeeLeaveModel->IsApproved = 1;
                    $EmployeeLeaveModel->ApprovedBy = Yii::$app->session['EmployeeID'];
                    $EmployeeLeaveModel->ApprovedDate = Date('Y-m-d'); 

                    $EmployeeLeaveModel->save();
                    Yii::$app->session->setFlash('leaverequested', "Leave Requested Successfully. ");
                return $this->redirect('index');

                }
            } else {
                   Yii::$app->session->setFlash('leaverequestedcancle', "Leave Requested Cancelled. ");
                return $this->redirect('index');
            }
        } else{ 

		$SelectLeaveType = Listitems::find()->where(['type'=>'leavetype'])->all();
        $LeaveType = (count($SelectLeaveType) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectLeaveType, 'ListItemID', 'Title');

        $query = new Query();
        $connection = Yii::$app->getDb();
        $role = strtolower(Yii::$app->session['Role']);
        if($role == 'admin' || $role == 'hr' || $role ='superadmin'){
             $EmployeeListForLeave = $connection->createCommand( "
            select E.EmployeeID,E.FullName from employee E where E.IsActive=1;
        ");
        }else{
       $EmployeeListForLeave = $connection->createCommand( "
            select E.EmployeeID,E.FullName from employee E where E.EmployeeID=".Yii::$app->session['EmployeeID']." or E.Supervisor=".Yii::$app->session['EmployeeID'].";
        ");}
        $ResultEmployeeListForLeave = $EmployeeListForLeave->queryall();
        $EmployeeList = (count($ResultEmployeeListForLeave) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($ResultEmployeeListForLeave, 'EmployeeID', 'FullName');


        $query1 = new Query();
        $Result = $query1->select(['LT.Title','L.Balance', 'L.Earned'])->where(['L.EmployeeID'=>Yii::$app->session['UserID']])->from('leave L')->leftJoin('listitems LT','LT.ListItemID = L.LeaveTypeID')->all();
        $CountLeaveRequest =count(Employeeleave::find()->where(['IsApproved'=>0, 'IsRejected'=>0])->all());

        $query2 = new Query();
        $ResultLeave = $query2->select(['concat(E.From," to ",E.To) DateRange', 'E.Reason','L.Title as LeaveType'])->where(['E.EmployeeID'=>$_SESSION['EmployeeID']])->from('employeeleave E')->leftJoin('listitems L','E.LeaveTypeID = L.ListItemID')->all();
        return $this->render('index',[
            'Result'=> $Result,
            'LeaveType'=> $LeaveType,
            'LeaveModel'=> $LeaveModel,
            'ResultLeave'=> $ResultLeave,
            'EmployeeList'=> $EmployeeList,
            'CountLeaveRequest'=> $CountLeaveRequest,
            'EmployeeLeaveModel'=> $EmployeeLeaveModel,
        ]);
       }
    }
    public function actionApprove()
    {
        $query3 = new Query();
        $ApproveLeave = $query3->select(['E.LeaveID', 'EM.EmployeeID', 'EM.FullName','E.EmployeeLeaveID','E.RejectedNote', 'E.IsApproved','E.IsRejected','concat(E.From," to ",E.To) DateRange', 'E.Reason','L.Title as LeaveType', 'E.NoOfDays'])->from('employeeleave E')/*->where(['IsApproved'=> '0', 'IsRejected' => '0'])*/->leftJoin('listitems L','E.LeaveTypeID = L.ListItemID')->leftjoin('employee EM', 'E.EmployeeID = EM.EmployeeID')->all();
        // print_r($ApproveLeave); die();

        return $this->render('approve',[
            'ApproveLeave'=>$ApproveLeave,
        ]);
    }
    public function actionApproveleave(){
        $Role = UserController::CheckRole("leave");
         $Identity = $_POST["identity"];
     if ($Role == true && $Identity != null && UserController::CheckUserAuthentication($Identity)){
           
        $Status = $_POST["status"];
        $Note = $_POST["remarks"];
        $EmployeeID = $_POST["employeeID"];
        $LeaveID = $_POST["leaveID"];
        $Days = $_POST["leaveDays"];

        $model = Employeeleave::findOne($Identity);
        if($Status == 'true'){
        //deduct the leave balance from 'leave' table after appvoval of the particular leave 

        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry= sprintf("UPDATE `leave` SET Balance = Balance - '%d' WHERE LeaveID = '%d' AND EmployeeID = '%d'",$Days, $LeaveID, $EmployeeID);
        $result = $connection->createCommand($qry);
        $res = $result->execute();

            $model->IsApproved = 1;
            $model->ApprovedBy = Yii::$app->session['UserID'];
            $model->ApprovedDate = Date('Y-m-d'); 
        }else{
            $model->IsRejected = 1;
            $model->ApprovedBy = Yii::$app->session['UserID'];
            $model->RejectedNote = $Note;
        }
        if($model->save()){
          return '{"result":true,"message":"Updated Successfully"}';
        }
     } 
    }

    public function actionLeavetypelist(){
    $Role = UserController::CheckRole('leave');
     if ($Role == true) {
        $EmployeeID = $_POST['data'];
        $query7 = new Query();
        $LeaveTypeList = $query7->select(['LeaveType.Title','L.LeaveID','L.LeaveTypeID', 'L.Balance'])->where(['L.EmployeeID'=>$EmployeeID])->from('leave L')->leftJoin('listitems LeaveType','L.LeaveTypeID = LeaveType.ListItemID')->all();
             \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
             return $LeaveTypeList;
     }

    }

}
  