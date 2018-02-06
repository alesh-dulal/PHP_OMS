<?php

namespace backend\modules\stock\controllers;

use Yii;
use backend\modules\stock\models\Items;
use backend\modules\stock\models\Unit;
use backend\modules\stock\models\ItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use backend\modules\user\models\Listitems;
use backend\modules\user\models\ListitemsSearch;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{
    /**
     * @inheritdoc
     */
    
    
    
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
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex()
    {

            $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Items model.
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
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Items();

        $SelectUnit = Listitems::find()->where(['type'=>'stockunit'])->all();
        $Unit = (count($SelectUnit) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectUnit, 'ListItemID', 'Title');

        $SelectCategory = Listitems::find()->where(['type'=>'stockcategory'])->all();
        $Category = (count($SelectCategory) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectCategory, 'ListItemID', 'Title');

      

        if ($model->load(Yii::$app->request->post())) {
            
            
            $model->InserstedBy=Yii::$app->user->id;
            $model->save();
            return $this->redirect(['index', 'id' => $model->ItemID]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'Unit' => $Unit,
                'Category' => $Category,
            ]);
        }
    }
    
        public function actionGetunit($ItemID){
        $Item= Items::findone($ItemID);
        $UnitID = $Item->UnitID;

        // get name from listitemid
        $Unit = Listitems::findOne($UnitID);
        //$UnitName = $Unit->Title;


       //echo "<pre>"; print_r($UnitName);

        echo \yii\helpers\Json::encode($Unit);
    }

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ItemID]);
        } else {

        $SelectUnit = Listitems::find()->where(['type'=>'stockunit'])->all();
        $Unit = (count($SelectUnit) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectUnit, 'ListItemID', 'Title');

        $SelectCategory = Listitems::find()->where(['type'=>'stockcategory'])->all();
        $Category = (count($SelectCategory) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectCategory, 'ListItemID', 'Title');

            return $this->render('update', [
                'model' => $model,
                'Unit' => $Unit,
                'Category' => $Category,
            ]);
        }
    }

    /**
     * Deletes an existing Items model.
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
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
