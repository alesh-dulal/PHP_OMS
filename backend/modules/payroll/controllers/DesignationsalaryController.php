<?php
namespace backend\modules\payroll\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\user\controllers\UserController;
use backend\modules\payroll\models\Designationsalary;

class DesignationsalaryController extends \yii\web\Controller
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
        $model = new Designationsalary();
        //query for showing in the grid
        $query = new Query();
        $designationSalaries = $query->select(['DesignationSalaryID','LI.Title', 'DesignationID', 'SalaryAmount'])->from('designationsalary DS')->leftjoin('listitems LI', 'LI.ListItemID=DS.DesignationID')->all();
        //end of query part
        return $this->render('index',[
            'model' => $model,
            'designationSalaries' => $designationSalaries,
        ]);
    }

    public function actionSavedata(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            $IsNewRecord = $_POST['isNewRecord'];
            if($IsNewRecord == 0){
                $model = new Designationsalary();
                $model->CreatedDate = date('Y-m-d');
                $model->CreatedBy =Yii::$app->session['UserID'];               
            }else{
                $model = Designationsalary::findOne($IsNewRecord);
                $model->UpdatedDate = date('Y-m-d');
                $model->UpdatedBy =Yii::$app->session['UserID'];
            }
            $designationName = $_POST["designationName"];
            $salaryAmount = $_POST["salaryAmount"];
            $model->DesignationID = $designationName;
            $model->SalaryAmount = $salaryAmount;
            $model->save();
            return '{"result":true,"message":"saved successfully"}';
        } 
    }
}/*End Of Class*/
