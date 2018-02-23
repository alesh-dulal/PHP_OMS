<?php


namespace backend\modules\user\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\user\models\Listitems;
use backend\modules\user\models\ListitemsSearch;

class SettingsController extends \yii\web\Controller
{
    
    public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             parent::__construct($id, $module, $config);
             $flag= in_array( "settings" ,$menusarray )?true:false;
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
                'only' => ['index','create', 'update', 'delete', 'view'],
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
    	 $model = new Listitems();

         $StockUnit = Listitems::find()->where(['IsActive'=>1, 'Type'=> "stockunit", 'ParentID'=>0])->all();
         $StockUnitParent = (count($StockUnit) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($StockUnit, 'ListItemID', 'Title');

        return $this->render('index',[
             'model' => $model,
        	 'StockUnitParent' => $StockUnitParent,
        ]);
    }

    public function actionSavedata()
      {
        $Role = UserController::CheckRole("settings");
        if ($Role == true) {
          $Identity = $_POST["identity"];
          if($Identity == 0){
            $model = new Listitems();
          }else{
            $model = Listitems::findOne($Identity);
          }
            $Title =  $_POST["title"];
            $Type = $_POST["type"];
            $Value = $_POST["value"];
            $Options = $_POST["options"];

            $model->Type = $Type;
            $model->Title = $Title;
            $model->Value = $Value;
            $model->Options = $Options;

            $model->CreatedBy = Yii::$app->user->id;
            $model->CreatedDate = Date('Y-m-d H:i:s');

          if($model->save()){
            return '{"result":true,"message":"saved successfully"}';
          }
        }         
      }

    public function actionRetrivedata(){
      $Role =UserController::CheckRole("settings");
      if ($Role == true) {
         $PostType = $_POST['type'];
        if($PostType=="stockunit"){
         $connection = Yii::$app->getDb();
         $command = $connection->createCommand( "select * FROM listitems U left join (SELECT ListItemID ID,Title Parent FROM listitems where Type='stockunit' and ParentID=0) As P on U.ParentID = P.ID where U.Type ='stockunit'");
        $Result = $command->queryAll();
        }
        else{
           $Result = Listitems::find()->where(['type'=>$PostType])->all();
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $Result;
      } 
    }
}
