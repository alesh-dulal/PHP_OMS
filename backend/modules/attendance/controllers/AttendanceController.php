<?php


namespace backend\modules\attendance\controllers;
use backend\modules\attendance\models\Attendance;
use backend\modules\user\models\Employee;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\modules\user\controllers\UserController;

class AttendanceController extends \yii\web\Controller
{
         public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
                if( in_array( "attendance" ,$menusarray ) )
                  {
                     $flag=TRUE;
                  }
                  else{
                      $flag=FALSE;
                  }
              parent::__construct($id, $module, $config);
              if($flag==FALSE)
            {
                 $this->redirect(Yii::$app->urlManager->baseUrl.'/dashboard');
                 
                 return;
            }
         }
     public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'find'],
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
         $model=new Attendance();
        $SelectEmployee = Employee::find()->where(['IsActive'=>1])->all();
        $Employee = (count($SelectEmployee) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectEmployee, 'EmployeeID', 'FullName');

        $attendance= Attendance::find()->all();
        $connection = Yii::$app->getDb();
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

        $date=(count($attendance) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($attendance, 'AttendanceID', 'AttnDate');
        return $this->render('index',[
           'Employee' => $Employee,
            'model'=>$model,
            'attendance'=>$attendance,
            'date'=>$date,
            'EmployeeList'=>$EmployeeList
        ]);
    }

    public function actionFind(){
        $Role= UserController::CheckRole("attendance");
        if ($Role==TRUE){
         if(isset($_POST)){
            if($_POST['data'])
        $daterange=explode("to",$_POST['data']);
            if($_POST['employee'])
               $employeeid=$_POST['employee'];
  
     $model = Attendance::find()->where(['between', 'AttnDate',$daterange[0],$daterange[1]])
    ->andWhere(['EmployeeID'=>$employeeid])->all();
   
     $htm=''; 
    foreach ($model as $row){
         $htm .='<tr>';
         $htm.='<td>'.$row->AttnDate.'</td>';
         $htm.='<td>'.$row->CheckIn.'</td>';
         $htm.='<td>'.$row->CheckOut.'</td>';
         $htm.='<td>'.$row->CheckInDiff.'</td>';
         $htm.='<td>'.$row->CheckOutDiff.'</td>';
         $htm.='<td>'.$row->WorkedTime.'</td>';
         $htm.='<td>'.$row->WorkedTimeDiff.'</td>';
         $htm.='<td>'.$row->Remarks.'</td>'; 
         $htm .='</tr>';
    }
    return $htm;
    
         }
    }
    
    
    }

}
