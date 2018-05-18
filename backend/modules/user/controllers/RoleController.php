<?php

namespace backend\modules\user\controllers;

use Yii;
use backend\modules\user\models\Role;
use backend\modules\user\models\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\user\models\Listitems;

use yii\filters\AccessControl;
/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             parent::__construct($id, $module, $config);
             $flag= in_array( "role" ,$menusarray )?true:false;
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

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();
        
        
        if ($model->load(Yii::$app->request->post())) {
            
            if ($model->MenuID != " ") {
               $model->MenuID = strtolower(implode(",", $model->MenuID));   
            }

            $model->CreatedBy = Yii::$app->user->id;
            $model->CreatedDate = Date('Y-m-d');
            
            $model->save();
            
            return $this->redirect('index');
            
        } else {

            $CheckList = Listitems::find()->where(['type'=>'menu'])->all();
            $Item = array();
            foreach($CheckList as $CkLst){
           $Item[$CkLst->Title] = ucfirst($CkLst->Title);
           }
            $model->MenuID=explode(',',$model->MenuID);

            return $this->renderAjax('create', [
                'model' => $model,
                'Item' => $Item,
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->MenuID != " ") {
               $model->MenuID = strtolower(implode(",", $model->MenuID));   
            }
            $model->UpdatedBy = Yii::$app->user->id;
            $model->UpdatedDate = Date('Y-m-d h:i:s');
            
            $model->save();
            return $this->redirect('index');
        } else {
            $CheckList = Listitems::find()->where(['type'=>'menu'])->all();
            $Item = array();
            foreach($CheckList as $CkLst){
           $Item[$CkLst->Title] = ucfirst($CkLst->Title);
           }            
            return $this->render('update', [
                'model' => $model,
                'Item' => $Item
            ]);
        }
    }

    /**
     * Deletes an existing Role model.
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
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
