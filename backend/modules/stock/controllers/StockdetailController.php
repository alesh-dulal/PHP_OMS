<?php

namespace backend\modules\stock\controllers;

use Yii;
use backend\modules\stock\models\Stockdetail;
use backend\modules\stock\models\Stock;
use backend\modules\stock\models\Items;
use backend\modules\stock\models\StockdetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\user\controllers\UserController;

use yii\filters\AccessControl;

/**
 * StockdetailController implements the CRUD actions for Stockdetail model.
 */
class StockdetailController extends Controller
{
    /**
     * @inheritdoc
     */
     public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             parent::__construct($id, $module, $config);
             $flag= in_array( "stockdetail" ,$menusarray )?true:false;
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
                'only' => ['index','view', 'create','damage','userindex','multisave','savedata','delete','update'],
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
     * Lists all Stockdetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockdetailSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
     
    }

    public function actionUserindex()
    {
        $searchModel = new StockdetailSearch();
        
        $user = \backend\modules\user\models\User::find()->all();
        $username = (count($user) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($user, 'UserId', 'UserName');
        $dataProvider = $searchModel->usersearch(Yii::$app->request->queryParams);
       
        return $this->render('userindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'username'=>$username,
            
        ]);
     
    }

    /**
     * Displays a single Stockdetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionDamage(){
        $model = new Stockdetail();
        if ($model->load(Yii::$app->request->post())) {
                $model->IsDamaged=1;
                $model->IsStock=0;
                $model->UserID=Yii::$app->user->id;
                $unit=Items::find()->where(['ItemID'=>$model->ItemID])->one();
                $model->UnitID= $unit->UnitID;
                $model->save();
           
                if(Stock::find()->where(['ItemID'=>$model->ItemID])->one()){
                    $stockModel = Stock::find()->where(['ItemID'=>$model->ItemID])->one();
                }
                if($model->IsStock==0){
                  if($stockModel->Qty < $model->Qty){
                    Yii::$app->session->setFlash('success', "Quantity you provided is not in the stock");
                   $val = "damage";
                    return $this->render('stockout', [
                'model' => $model,
                        'val'=>$val,]);
                }
                else{
                    $stockModel->Qty = $stockModel->Qty - $model->Qty;//reduce quantity of stockdetails into stock
                    $stockModel->ItemID=$model->ItemID;
                    $stockModel->save(); 
                }
               return $this->redirect(['stock/index', 'id' => $model->StockDetailID]);
            } 
        }
          else{
              $val = "damage";
        return $this->render('stockout', [
                'model' => $model,
                'val'=>$val,
            
            ]);
              }
    }

    
    protected function savedata($itemID,$qty,$un,$exDate,$remarks) {
       $model=new Stockdetail();
        $model->ItemID=$itemID;
        $model->Qty=$qty;
        $model->ExpiryDate=$exDate;
        $model->UnitID=$un;
        $model->Remarks=$remarks;
        $model->InsertedBy=Yii::$app->session['EmployeeID'];
        $model->IsStock=1;


        $model->save();

                if(Stock::find()->where(['ItemID'=>$model->ItemID])->one()){
                    $stockModel = Stock::find()->where(['ItemID'=>$model->ItemID])->one();
                   }
                else {
                       $stockModel = new Stock();
                       $stockModel->IsActive ='yes';
                       $stockModel->Qty=0;
                   }
            if($model->IsStock==1){
                $stockModel->Qty = $stockModel->Qty + $model->Qty;
                $stockModel->ItemID=$model->ItemID;
                $stockModel->save();
            }
           return $this->redirect(['stock/index', 'id' => $model->StockDetailID]);
    }

        public function actionStockin(){
            
        $model = new Stockdetail();

            return $this->render('_form', [
                'model' => $model,
                  ]);
        
    }
    
    public function actionStockout(){
          $model = new Stockdetail();
          if ($model->load(Yii::$app->request->post())) {
                $model->InsertedBy=Yii::$app->user->id;
                $model->IsStock=0;
                $unit=Items::find()->where(['ItemID'=>$model->ItemID])->one();
                $model->UnitID= $unit->UnitID;
                $model->save();
                
                if(Stock::find()->where(['ItemID'=>$model->ItemID])->one()){
                    $stockModel = Stock::find()->where(['ItemID'=>$model->ItemID])->one();
                }
                 if($model->IsStock==0){
                      if($stockModel->Qty < $model->Qty){
                        Yii::$app->session->setFlash('success', "Quantity you provided is not in the stock");
                       $val = "stockout";
                        return $this->render('stockout', [
                    'model' => $model,
                            'val'=>$val,]);
                    }
                    else{
                        $stockModel->Qty = $stockModel->Qty - $model->Qty;//reduce quantity of stockdetails into stock
                        $stockModel->ItemID=$model->ItemID;
                        $stockModel->save(); 
                    }
            }
            return $this->redirect(['stock/index', 'id' => $model->StockDetailID]);
        } 
        else {
            $val = "stockout";
            return $this->render('stockout', [
                'model' => $model,
                'val'=>$val,
            ]);
        }
    }
   
    /**
     * Creates a new Stockdetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    /**
     * Updates an existing Stockdetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->StockDetailID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Stockdetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Stockdetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Stockdetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stockdetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionMultisave(){
$Role= UserController::CheckRole("stockdetail");
        if ($Role==TRUE){
        if(isset($_POST)){
            if($_POST['itemID'])
                $itemID = $_POST['itemID'];
             if($_POST['Qty'])
                $QTY = $_POST['Qty'];
             if($_POST['exDate'])
                $exeDate = $_POST['exDate'];
             if($_POST['un'])
                $unit = $_POST['un'];
              if($_POST['remarks'])
                $remark = $_POST['remarks'];
              else $remark = NULL;

             $item= sizeof($itemID);
              
             for ($i=0;$i<=$item-1;$i++){

                 if($itemID[$i]== NULL || $QTY[$i] == NULL ||$unit[$i] == NULL)
                    continue; 
        $this->savedata($itemID[$i],$QTY[$i],$unit[$i],$exeDate[$i],$remark) ;


    }
    }
}
    }
}
