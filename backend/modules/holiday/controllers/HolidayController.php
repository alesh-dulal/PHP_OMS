<?php

namespace backend\modules\holiday\controllers;

use Yii;
use backend\modules\holiday\models\Holiday;
use backend\modules\holiday\models\HolidaySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * HolidayController implements the CRUD actions for Holiday model.
 */
class HolidayController extends Controller
{
    /**
     * @inheritdoc
     */
     public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             parent::__construct($id, $module, $config);
             $flag= in_array( "holiday" ,$menusarray )?true:false;
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
     * Lists all Holiday models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HolidaySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Holiday model.
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
     * Creates a new Holiday model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Holiday();
        
            $findyear = \backend\modules\attendance\models\Year::find()->all();
            $year = (count($findyear) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($findyear, 'YearID', 'Name');


        if ($model->load(Yii::$app->request->post())) {
            
            $model->InsertedBy=Yii::$app->user->id;
             $model->save();
            return $this->redirect(['index', 'id' => $model->HolidayID]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'year'=>$year,
            ]);
        }
    }

    /**
     * Updates an existing Holiday model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $findyear = \backend\modules\attendance\models\Year::find()->all();
        $year = (count($findyear) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($findyear, 'YearID', 'Name');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->HolidayID]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'year'=>$year,
            ]);
        }
    }

    /**
     * Deletes an existing Holiday model.
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
     * Finds the Holiday model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Holiday the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Holiday::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
