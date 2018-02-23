<?php

namespace backend\modules\user\controllers;

use Yii;
use backend\modules\user\models\User;
use backend\modules\user\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\user\models\Userlog;
use backend\modules\user\models\Employee;

use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    
     public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             parent::__construct($id, $module, $config);
             $flag= in_array( "user" ,$menusarray )?true:false;
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
     * Lists all User models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new User();


        if ($model->load(Yii::$app->request->post())) {
            $model->CreatedBy = Yii::$app->user->id;
            $model->CreatedDate = Date('Y-m-d');
             $model->save();
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * 
     * @param type $menuName name of menu
     */

    public static function CheckRole($menuName){
// check menu from session Menu if exist return true else false
        $menus=Yii::$app->session['Menus'];
        $menusarray=(explode(",",$menus)); 
        return in_array( $menuName ,$menusarray )?true:false;
    }

    /** 
     * 
     * @param type $userID User table login
     */

    public function CheckUserAuthentication($userID){
        $role = Yii::$app->session['Role'];
        if(strtolower($role)== "admin" ||strtolower($role)== "hr"||strtolower($role)== "superadmin"){return true;}
        elseif(strtolower($role)== "supervisor"){return EmployeeController::CheckSuperviser($userID)?true:false; }
        else{return false;}
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
