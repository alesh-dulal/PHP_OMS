<?php

namespace backend\modules\user\controllers;

use Yii;
use backend\modules\user\models\User;
use backend\modules\user\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\user\models\Userlog;
use backend\modules\user\models\Virtualemployee;
use backend\modules\user\models\VirtualemployeeSearch;

use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\db\Query;
/**
 * VirtualemployeeController implements the CRUD actions for Virtualemployee model.
 */
class VirtualemployeeController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function __construct($id, $module, $config = [])
    {
        $menus=Yii::$app->session['Menus'];
        $menusarray=(explode(",",$menus)); 
        parent::__construct($id, $module, $config);
        $flag= in_array( "virtualemployee" ,$menusarray )?true:false;
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
                'only' => ['index','create', 'update', 'view'],
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
        $searchModel = new VirtualemployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
     $model = new \backend\modules\user\models\Virtualemployee();
     $RandomNumber = mt_rand();
    if ($model->load(Yii::$app->request->post())) {
        if ($model->validate()) 
        {
            $EmployeeName = $model->FullName;
            $Image = UploadedFile::getInstance($model,'Image');
            if ($Image){
                $ext = pathinfo($Image, PATHINFO_EXTENSION);
                $filename = str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/profile/";
                
                $Image->saveAs($path."/".$filename);
                $model->Image = $filename;    
            }

            $model->CreatedBy= Yii::$app->session['EmployeeID'];
            $model->CreatedDate= Date('Y-m-d H:i:s');
            // echo "<pre>"; print_r($model); die();
            $model->save();
            return $this->redirect('index');
        }
    }
        $query = new Query();
       $connection = Yii::$app->getDb();
       $command = $connection->createCommand( "
        select E.EmployeeID, E.FullName from employee E LEFT JOIN role R on E.RoleID = R.RoleID where R.Name != 'employee'
        ;");
       $SelectSupervisor = $command->queryall();
        $Supervisor = (count($SelectSupervisor) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectSupervisor, 'EmployeeID', 'FullName');
    return $this->render('create', [
        'model' => $model,
        'Supervisor' => $Supervisor
    ]);
    }


    public function actionUpdate($id)
    {
         $RandomNumber = mt_rand();
        $model = $this->findModel($id);

        $oldProfile = $model->Image;
        if ($model->load(Yii::$app->request->post())) {
            $EmployeeName = $model->FullName;
            $Image = UploadedFile::getInstance($model,'Image');
            if ($Image){
                $ext = pathinfo($Image, PATHINFO_EXTENSION);
                $filename = str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/profile/";
                if ($oldProfile && file_exists($path.$oldProfile)) {
                    unlink($path.$oldProfile);
                } 
                $Image->saveAs($path."/".$filename);
                $model->Image = $filename;    
            }else{
                $model->Image = $oldProfile;    
            }
            $model->UpdatedBy= Yii::$app->session['EmployeeID'];
            $model->UpdatedDate= Date('Y-m-d H:i:s');
            if($model->save()){
            return $this->redirect('index');}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Virtualemployee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
