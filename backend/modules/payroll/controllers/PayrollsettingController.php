<?php
namespace backend\modules\payroll\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\payroll\models\Payrollsetting;
use backend\modules\user\controllers\UserController;

class PayrollsettingController extends \yii\web\Controller
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
        $model = new Payrollsetting();
        $query = new Query();
        $allowances = $query->select(['OrderNo','PayrollSettingID','IsAllowance', 'Title', 'Amount', 'Formula', '(
			    CASE
			    WHEN IsAllowance = 0 then "Allowance"
			    ELSE "Deduction"
			    END
			) as SettingType'])->from('payrollsetting')->where(['IsActive'=>1])->all();

        return $this->render('index',[
        	'model' => $model,
        	'allowances' => $allowances,

        ]);
    }

    public function actionSavedata(){
        $Role = UserController::CheckRole("payroll");
        if($Role == true){
            $IsNewRecord = $_POST['isNewRecord'];
            if($IsNewRecord == 0){
                $model = new Payrollsetting();
                $model->CreatedDate = date('Y-m-d');
                $model->CreatedBy =Yii::$app->session['UserID'];               
            }else{
                $model = Payrollsetting::findOne($IsNewRecord);
                $model->UpdatedDate = date('Y-m-d');
                $model->UpdatedBy =Yii::$app->session['UserID'];
            }

            $type = $_POST["isAllowance"];
            $title = $_POST["title"];
            $amount = $_POST["amount"];
            $formula = $_POST["formula"];
            $orderNo = $_POST["orderNo"];

            $model->IsAllowance = $type;
            $model->Title = $title;
            $model->Amount = $amount;
            $model->Formula = $formula;
            $model->OrderNo = $orderNo;
            $model->save();
            return '{"result":true,"message":"saved successfully"}';
        } 
    }

}
