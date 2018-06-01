<?php
namespace backend\modules\user\controllers;
use Yii;
use backend\modules\user\models\Listitems;
use backend\modules\user\models\ListitemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
* ListitemsController implements the CRUD actions for Listitems model.
*/
class ListitemsController extends Controller
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
* Lists all Listitems models.
* @return mixed
*/
    public function actionIndex()
    {
        $searchModel = new ListitemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
* Displays a single Listitems model.
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
* Creates a new Listitems model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
    public function actionCreate()
    {
        $model = new Listitems();
        if ($model->load(Yii::$app->request->post())) {
            $model->CreatedBy = Yii::$app->user->id;
            $model->CreatedDate = Date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /**
* Updates an existing Listitems model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param integer $id
* @return mixed
*/
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->UpdatedBy = Yii::$app->user->id;
            $model->UpdatedDate = Date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
* Deletes an existing Listitems model.
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
* Finds the Listitems model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return Listitems the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
    protected function findModel($id)
    {
        if (($model = Listitems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
