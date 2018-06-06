<?php
namespace backend\modules\report\controllers;

use Yii;
use yii\db\Query;
use backend\modules\report\models\Customreport;
use backend\modules\user\controllers\UserController;

class ReportController extends \yii\web\Controller
{
    public function __construct($id, $module, $config = [])
    {
        $menus=Yii::$app->session['Menus'];
        $menusarray=(explode(",",$menus)); 
        parent::__construct($id, $module, $config);
        $flag= in_array( "report" ,$menusarray )?true:false;
        if($flag==FALSE)
        {
            $this->redirect(Yii::$app->urlManager->baseUrl.'/dashboard');
            return false;
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView(){
        $model= Customreport::find()->all();
        return $this->render('view', [
            'model' => $model]);
    }

    public function actionGetreport(){
        $Role= UserController::CheckRole("report");
        if ($Role==TRUE){
            $date=$_POST['daterange'];
            $daterange=explode("to",$date);
            $name=$_POST['name'];
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($name);
            $Result = $command->queryall();
            $ColumnsArray= (count($Result)>0?array_keys($Result[0]):''); 
            $Columns=json_encode($ColumnsArray);
            $Record = json_encode($Result);
            $res='{"record":'.$Record.',"columns":'.$Columns.'}';
            return $res;
        } 
    }

    public function actionSave(){
        $Role= UserController::CheckRole("report");
        if ($Role==TRUE){
            $daterange=$_POST['daterange'];
            $select=$_POST['query'];
            $name=$_POST['name'];
            $column=$_POST['column'];
            $model=new CustomReport();
            $model->Name=$name;
            $model->Query=$select;
            $model->SelectColumn=$column;
            $model->CreatedBy=Yii::$app->user->id;
            if($daterange == 'true'){
                $model->DateRangeEnabled = 1;
            }else{
                $model->DateRangeEnabled = 0;
            }
            $model->save(); 
            return $this->redirect('index');            
        }
    }
    
    public function actionRetrieve(){
        $Role= UserController::CheckRole("report");
        if ($Role==TRUE){
            if(isset($_POST)){
                if($_POST['query'])
                    $Getdata=$_POST['query'];
                $query = new Query();
                $connection = Yii::$app->getDb();
                // Check if SELECT is in the query
                if (preg_match('/SELECT/', strtoupper($Getdata)) != 0) {
                    // Array with forbidden query parts
                    $disAllow = array(
                        'INSERT',
                        'UPDATE',
                        'DELETE',
                        'RENAME',
                        'DROP',
                        'CREATE',
                        'TRUNCATE',
                        'ALTER',
                        'COMMIT',
                        'ROLLBACK',
                        'MERGE',
                        'CALL',
                        'EXPLAIN',
                        'LOCK',
                        'GRANT',
                        'REVOKE',
                        'SAVEPOINT',
                        'TRANSACTION',
                        'SET',
                    );
                    $disAllow = implode('|', $disAllow);
                    if (preg_match('/('.$disAllow.')/', strtoupper($Getdata)) == 0) {
                        $command = $connection->createCommand($Getdata);
                        $Result = $command->queryall();
                        $ColumnsArray= (count($Result)>0?array_keys($Result[0]):''); 
                        $Columns=json_encode($ColumnsArray);
                        $Record = json_encode($Result);
                        $res='{"record":'.$Record.',"columns":'.$Columns.'}';
                    }
                }
                return $res;
            }
        }
    }
}
