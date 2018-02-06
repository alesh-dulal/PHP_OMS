<?php


namespace backend\modules\dashboard\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii;
use yii\db\Query;

use backend\modules\holiday\models\Holiday;

use backend\modules\user\models\Employee;
use backend\modules\dashboard\models\Poststatus;
use backend\modules\user\controllers\UserController;


/**
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
    /**
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

        foreach($EmployeeLeave as $employee){
               $Event = new \yii2fullcalendar\models\Event();
                    $Event->title = $employee['Name'];
                    $Event->start = $employee['From'];
                    $Event->end = $employee['To'];
                    $events[] = $Event;

                    }        
        return $this->render('index',[
            'Employee' => $Employee,
            'PostStatus'=>$PostStatus,
            'model'=>$model,
            'events'=>$events,
            'LeaveToday'=>$LeaveToday
              ]
             );
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
}
