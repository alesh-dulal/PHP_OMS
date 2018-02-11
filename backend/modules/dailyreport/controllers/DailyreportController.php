<?php

namespace backend\modules\dailyreport\controllers;

use backend\modules\user\controllers\UserController;
use backend\modules\dailyreport\models\Dailyreport;
use backend\modules\user\models\Employee;
use backend\modules\user\models\Role;
use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\db\Query;
use backend\modules\leave\models\Employeeleave;

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
         $model=new Dailyreport();
         $Employee=new Employee();
         $query = new Query();
         $connection = Yii::$app->getDb();
         $emp=$query->select(['E.FullName AS EmpName','E.UserID','E.EmployeeID','D.*','U.UserName AS UName' ])  
                        ->from('dailyreport D')
                        ->leftJoin('employee E', 'D.VerifiedBy =E.EmployeeID')->leftJoin('user U','D.CreatedBy=U.UserID')->orderBy(['D.CreatedTime'=>SORT_DESC])->all();
  // echo "<pre>"; print_r($emp); die();
         $CurrentEmployeeID =Dailyreport::find()->where(['CreatedBy'=>Yii::$app->session['EmployeeID']])->andWhere(['IsPending'=>1])->one();
         $CountSubmittedReport =count(Dailyreport::find()->where(['IsVerified'=>0])->andWhere(['IsPending'=>0])->all());
          $role = strtolower(Yii::$app->session['Role']);
         if($role == 'admin' || $role == 'hr' || $role ='superadmin'){
             $EmployeeListForReport = $connection->createCommand( "
            select E.EmployeeID,E.FullName from employee E where E.IsActive=1;
        ");
        }else{
         $EmployeeListForReport = $connection->createCommand( "
            select E.EmployeeID,E.FullName from employee E where E.EmployeeID=".Yii::$app->session['EmployeeID']." or E.Supervisor=".Yii::$app->session['EmployeeID'].";
        ");}
        $ResultEmployeeListForReport = $EmployeeListForReport->queryall();
        $EmployeeList = (count($ResultEmployeeListForReport) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($ResultEmployeeListForReport, 'EmployeeID', 'FullName');
                  
         return $this->render('index',[
            'model'=>$model,
            'emp'=>$emp, 
            'countreport'=>$CountSubmittedReport,
            'EmployeeList'=>$EmployeeList,
             'Employee'=>$Employee,
             'CurrentEmployeeID'=>$CurrentEmployeeID,
             
        ]);
    }
    
    public function actionSavetask(){
     $Role= UserController::CheckRole("dailyreport");
        if ($Role==TRUE){
             if(isset($_POST)){
            if($_POST['totaltask'])
                $totaltask=$_POST['totaltask'];
             if($_POST['report'])
                 $report=$_POST['report'];
             if($_POST['submittedDate'])
                 $submittedDate=$_POST['submittedDate'];
             else $submittedDate=NULL;
             if($_POST['user'])
                $user=$_POST['user'];
              else $user=NULL;
             }
         
            if($user === NULL){
                        $Taskmodel=new Dailyreport();
                        $Taskmodel->TotalTask=$totaltask;
                        $Taskmodel->Report=$report;
                        $Taskmodel->ReportDate=date('Y-m-d');
                        $Taskmodel->CreatedBy=Yii::$app->session['EmployeeID'];
                        
                        $Check= Dailyreport::find()->where(['CreatedBy'=>Yii::$app->session['EmployeeID']])
                        ->andWhere(['ReportDate'=>date('Y-m-d')])->one();
                        
                        if($Check==NULL){
                            $Taskmodel->save();
                            $this->SaveLog('Report Submitted', '');
                            return $this->redirect(['dailyreport/index', 'id' => $Taskmodel->DailyReportID]);
                        }
                        
                    else if($Check->ReportDate==$Taskmodel->ReportDate){
                            Yii::$app->session->setFlash('danger',"You have already saved report today");
                            return $this->redirect(['dailyreport/index', 'id' => $Taskmodel->DailyReportID]);
                    }
                    } 
           else{
            $model = Dailyreport::findOne($user); 
            $model->TotalTask=$totaltask;
            $model->Report=$report;
            $model->ReportDate=$submittedDate;
            $model->CreatedBy=Yii::$app->session['EmployeeID'];
            $model->IsPending=0;
            $model->save();
           return $this->redirect(['dailyreport/index', 'id' => $model->DailyReportID]);
           }
        }
        }
    
    public function actionApprovereport(){
        $Role= UserController::CheckRole("dailyreport");
        $Identity = $_POST["identity"];
        if ($Role==TRUE &&$Identity!=NUll && UserController::CheckUserAuthentication($Identity)){
        $Status = $_POST["status"];
       $Remarks = $_POST["remarks"];
       $model = Dailyreport::findOne($Identity);
        if($Status == 'true'){
            $model->IsVerified = 1;
            $model->VerifiedBy = Yii::$app->session['UserID'];
            $model->VerifiedDate = Date('Y-m-d H:i:s');   
        }else{
            $model->IsVerified = 0;
            $model->VerifiedBy = Yii::$app->session['UserID'];
            $model->Remarks = $Remarks;
        }
         $model->save();
 return $this->redirect(['dailyreport/index?tab=report']);
  
        }
        
    
    }
    public function actionAdddate(){
         $Role= UserController::CheckRole("dailyreport");
          $EmployeeId= $_POST["empid"];
          
        if ($Role==TRUE &&$EmployeeId!=NUll && UserController::CheckUserAuthentication($EmployeeId)){
         $Date = $_POST["date"];
         $AddDate = new Dailyreport();
         $AddDate->CreatedBy=$EmployeeId;
         $AddDate->ReportDate=$Date;
         $AddDate->TotalTask=0;
         $AddDate->Report="";
         $AddDate->IsPending=1;
         
         $AddDate->save();
        return $this->redirect(['dailyreport/index', 'id' => $AddDate->DailyReportID]);
       }
    }
}
