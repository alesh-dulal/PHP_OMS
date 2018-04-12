<?php


namespace backend\modules\attendance\controllers;
use backend\modules\attendance\models\Attendance;
use backend\modules\user\models\Employee;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\modules\user\controllers\UserController;
use yii\db\Query;

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
        return $this->render('index',[
            'model'=>$model,
        ]);
    }

    public function actionFind()
    {
        $checkInDiffTotal=0; 
        $checkOutDiffTotal=0; 
        $workedTimeTotal=0; 
        $workedTimeDiffTotal=0;
        $count = 0;
        $htm = "";
        $Role= UserController::CheckRole("attendance");
        if ($Role==TRUE)
        {
            if($_POST['data'])
                $daterange=explode("to",$_POST['data']);
            if($_POST['employee'])
                $employeeid=$_POST['employee'];
            $model = Attendance::find()->where(['between', 'AttnDate',$daterange[0],$daterange[1]])
    ->andWhere(['EmployeeID'=>$employeeid])->distinct('AttnDate')->orderBy([
        'AttnDate'=>SORT_DESC
    ])->all();
            try 
            {
                if($model != NULL  && sizeof($model) > 0){
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

                        $checkInDiffTotal += $this->timeToSec($row->CheckInDiff);
                        $checkOutDiffTotal += $this->timeToSec($row->CheckOutDiff);
                        $workedTimeTotal += $this->timeToSec($row->WorkedTime);
                        $workedTimeDiffTotal += $this->timeToSec($row->WorkedTimeDiff);
                        $count++;
                    }
                        $htm.= '<tr>';
                        $htm.='<td colspan="3">Total '.($count = 0 ? "":$count ).' Days </td>';
                        $htm.='<td>'.$this->getTime($checkInDiffTotal).'</td>';
                        $htm.='<td>'.$this->getTime($checkOutDiffTotal).'</td>';
                        $htm.='<td>'.$this->getTime($workedTimeTotal).'</td>';
                        $htm.='<td>'.$this->getTime($workedTimeDiffTotal).'</td>';
                        $htm.= '</tr>';
                }
            } 
            catch (Exception $e) 
            {
                return $e;
            }
           
        }
                return $htm;                
    }


        function getTime($duration) {
            $hours = floor($duration / 3600);
            $minutes = floor(($duration / 60) % 60);
            $seconds = $duration % 60;
        return "$hours:$minutes:$seconds";
        }

        function timeToSec($string){
            list($hour, $min, $sec) = explode(':', $string);
         return $hour*3600+$min*60+$sec;
        }
}
