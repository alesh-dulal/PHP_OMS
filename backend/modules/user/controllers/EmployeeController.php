<?php

namespace backend\modules\user\controllers;

use Yii;
use backend\modules\user\models\Employee;
use backend\modules\user\models\Listitems;
use backend\modules\user\models\Reference;
use backend\modules\user\models\ListitemsSearch;
use backend\modules\user\models\EmployeeSearch;

use backend\modules\user\models\User;
use backend\modules\user\models\UserSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\user\models\Role;
use backend\modules\user\models\RoleSearch;

use yii\web\UploadedFile;
use yii\db\Query;

use backend\controllers\SiteController;

use yii\filters\AccessControl;

use backend\modules\user\models\Excel;
use yii\data\Pagination;
/**
 * EmployeeController implements the CRUD actions for Employee model.
 */

class EmployeeController extends Controller
{

        public function __construct($id, $module, $config = [])
         {
             $menus=Yii::$app->session['Menus'];
             $menusarray=(explode(",",$menus)); 
             parent::__construct($id, $module, $config);
             $flag= in_array( "employee" ,$menusarray )?true:false;
            if($flag==FALSE)
            {
                $this->redirect(Yii::$app->urlManager->baseUrl.'/dashboard');
                 return false;
            }
         }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','create', 'update', 'delete', 'view','myinfo'],
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
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();
        $RandomNumber = mt_rand();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->CreatedBy = Yii::$app->user->id;
            $model->CreatedDate = Date('Y-m-d H:i:s');
            $EmployeeName = $model->FullName;

            $Image = UploadedFile::getInstance($model,'Image');
            if ($Image){
                $ext = pathinfo($Image, PATHINFO_EXTENSION);
                $filename =  str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/profile/";
                $Image->saveAs($path."/".$filename);
                $model->Image = $filename;
            }

            $CitizenFile = UploadedFile::getInstance($model,'CitizenFile');
            if ($CitizenFile){
                $ext = pathinfo($CitizenFile, PATHINFO_EXTENSION);
                $filename = str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/citizenship/";
                
                $CitizenFile->saveAs($path."/".$filename);
                $model->CitizenFile = $filename;    
            }

            $CITFile = UploadedFile::getInstance($model,'CITFile');
            if ($CITFile){
                $ext = pathinfo($CITFile, PATHINFO_EXTENSION);
                $filename = str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/CIT";
                
                $CITFile->saveAs($path."/".$filename);
                $model->CITFile = $filename;    
            }

            $PANFile = UploadedFile::getInstance($model,'PANFile');
            if ($PANFile){
                $ext = pathinfo($PANFile, PATHINFO_EXTENSION);
                $filename =  str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/PAN";
                $PANFile->saveAs($path."/".$filename);
                $model->PANFile = $filename;    
            } 
    // Save in User table 

            $UserModel = new User();
             $UserModel->UserName = $model->Email;
             $Password = "topnepal@1";
             $UserModel->Password = md5($Password);
             $UserModel->Email = $model->Email;
             $UserModel->auth_key = 145;
             $UserModel->CreatedBy = Yii::$app->user->id;
             $UserModel->CreatedDate = Date('Y-m-d');
             
             if ($UserModel->save()) {
                 $model->UserID = $UserModel->UserId;
                 $model->save();
                 }
                return $this->redirect('index');
        } else {

            $SelectDepartment = Listitems::find()->where(['type'=>'department'])->all();
        $Department = (count($SelectDepartment) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectDepartment, 'ListItemID', 'Title');

        $SelectDesignation = Listitems::find()->where(['type'=>'designation'])->all();
        $Designation = (count($SelectDesignation) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectDesignation, 'ListItemID', 'Title');

        $SelectRole = Role::find()->where(['IsActive'=>1])->all();
        $Role = (count($SelectRole) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectRole, 'RoleID', 'Name');

        $SelectRoom = Listitems::find()->where(['type'=>'room'])->all();
        $Room = (count($SelectRoom) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectRoom, 'ListItemID', 'Title');

        $SelectBiometric = Listitems::find()->where(['type'=>'biometric'])->all();
        $Biometric = (count($SelectBiometric) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectBiometric, 'ListItemID', 'Title');


        $query = new Query();
       $connection = Yii::$app->getDb();
       $command = $connection->createCommand( "
        select E.EmployeeID, E.FullName from employee E LEFT JOIN role R on E.RoleID = R.RoleID where R.Name != 'employee'
        ;");
       $SelectSupervisor = $command->queryall();
        $Supervisor = (count($SelectSupervisor) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectSupervisor, 'EmployeeID', 'FullName');

        // print_r($Supervisor); die();

        $SelectShift = Listitems::find()->where(['type'=>'shift'])->all();
        $Shift = (count($SelectShift) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectShift, 'ListItemID', 'Title');

            return $this->render('create', [
                'model' => $model,
                'Department' => $Department,
                'Designation' => $Designation,
                'Role' => $Role,
                'Room' => $Room,
                'Biometric' => $Biometric,
                'Shift' => $Shift,
                'Supervisor' => $Supervisor,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $RandomNumber = mt_rand();
        $model = $this->findModel($id);

        $oldProfile = $model->Image;
        $oldCitizenFile = $model->CitizenFile;
        $oldCITFile = $model->CITFile;
        $oldPANFile= $model->PANFile;

        $SelectDepartment = Listitems::find()->where(['type'=>'department'])->all();
        $Department = (count($SelectDepartment) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectDepartment, 'ListItemID', 'Title');

        $SelectDesignation = Listitems::find()->where(['type'=>'designation'])->all();
        $Designation = (count($SelectDesignation) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectDesignation, 'ListItemID', 'Title');

        $SelectRole = Role::find()->where(['IsActive'=>1])->all();
        $Role = (count($SelectRole) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectRole, 'RoleID', 'Name');

        $SelectRoom = Listitems::find()->where(['type'=>'room'])->all();
        $Room = (count($SelectRoom) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectRoom, 'ListItemID', 'Title');

        $SelectBiometric = Listitems::find()->where(['type'=>'biometric'])->all();
        $Biometric = (count($SelectBiometric) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectBiometric, 'ListItemID', 'Title');

        $SelectSupervisor = Employee::find()->where('Role' != 'employee')->all();
        $Supervisor = (count($SelectSupervisor) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectSupervisor, 'EmployeeID', 'FullName');

        $SelectShift = Listitems::find()->where(['type'=>'shift'])->all();
        $Shift = (count($SelectShift) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($SelectShift, 'ListItemID', 'Title');

        //show image on update form and delete the old existing file on update
        $query = new Query();
        $connection = Yii::$app->getDb();
        $SelectImage = $connection->createCommand( "
            select Image, CITFile, CitizenFile, PANFile from `employee` where EmployeeID=".$id.";
        ");
        $Images = $SelectImage->queryone();


         if ($model->load(Yii::$app->request->post())) {
                $EmployeeName = $model->FullName;

            $Image = UploadedFile::getInstance($model,'Image');
            if ($Image){
                $ext = pathinfo($Image, PATHINFO_EXTENSION);
                $filename =  str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/profile/";
                //delete  the existing image

                if ($oldProfile && file_exists($path.$oldProfile)) {
                    unlink($path.$oldProfile);
                } 

                $Image->saveAs($path."/".$filename);
                $model->Image = $filename;
            }else{
                
                $model->Image = $Images['Image'];    
            }

            $CitizenFile = UploadedFile::getInstance($model,'CitizenFile');
            if ($CitizenFile){
                $ext = pathinfo($CitizenFile, PATHINFO_EXTENSION);
                $filename = str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/citizenship/";

                //delete  the existing image

                if ($oldCitizenFile && file_exists($path.$oldCitizenFile)) {
                    unlink($path.$oldCitizenFile);
                } 
                
                $CitizenFile->saveAs($path."/".$filename);
                $model->CitizenFile = $filename;    
            }else{
                
                $model->CitizenFile = $Images['CitizenFile'];    
            }

            $CITFile = UploadedFile::getInstance($model,'CITFile');
            if ($CITFile){
                $ext = pathinfo($CITFile, PATHINFO_EXTENSION);
                $filename = str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/CIT/";

                 //delete  the existing image
                if ($oldCITFile && file_exists($path.$oldCITFile)) {
                    unlink($path.$oldCITFile);
                }
                
                $CITFile->saveAs($path."/".$filename);
                $model->CITFile = $filename;    
            }else{
                
                $model->CITFile = $Images['CITFile'];    
            }

            $PANFile = UploadedFile::getInstance($model,'PANFile');
            if ($PANFile){
                $ext = pathinfo($PANFile, PATHINFO_EXTENSION);
                $filename =  str_replace(" ","_",$EmployeeName)."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/PAN/";
                //delete  the existing image
               if ($oldPANFile && file_exists($path.$oldPANFile)) {
                    unlink($path.$oldPANFile);
                }

                $PANFile->saveAs($path."/".$filename);

                $model->PANFile = $filename;    
            }else{
                
                $model->PANFile = $Images['PANFile'];    
            }

            $model->UpdatedBy = Yii::$app->user->id;
            $model->UpdatedDate = Date('Y-m-d H:i:s');
            if($model->save(false)){
                return $this->redirect('index');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'Department' => $Department,
                'Designation' => $Designation,
                'Role' => $Role,
                'Room' => $Room,
                'Biometric' => $Biometric,
                'Shift' => $Shift,
                'Supervisor' => $Supervisor,
                // 'ProfileImage' => $ProfileImage,
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
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
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public static function getOptions() {
        $data = static::find()->where(['status'=>1])->all();
        $value = (count($data) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($data, 'id', 'fullname'); 
        return $value;
    }
    
    public function CheckSuperviser($userID)
    {
       $loggedInUserID=Yii::$app->session['UserID'];
        $query = new Query();
        $connection = Yii::$app->getDb();
       $qry= sprintf("SELECT supervisor FROM employee where EmployeeID='%d' and Supervisor = '%d'",$userID, $loggedInUserID);
       $result=$connection->createCommand($qry);
       $res=$result->execute();
       return($res == 1)?true:false;
    }

    public function actionImportexcel()
    {
    if (!empty($_FILES['file']['name']))
        $uploadedFile = '';
        {
        if (!empty($_FILES["file"]["type"]))
            {
            $fileName = time().'_'.$_FILES['file']['name'];
            $valid_extensions = array(
                "xls",
                "xlsx"
            );

            $temporary = explode(".", $_FILES["file"]["name"]);
            $file_extension = end($temporary);

            if (!in_array($file_extension,$valid_extensions)){
                Yii::$app->session->setFlash('fileextensionerror', "Attempt to upload invalid file. Check and upload valid file"); 

                return $this->redirect(['index']);

            }else{

                $sourcePath = $_FILES['file']['tmp_name'];
                $targetPath = "uploads/".$fileName;
                move_uploaded_file($sourcePath, $targetPath);

                $inputFile = 'uploads/'.$fileName;

                    try{
                        $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFile);
                    }catch(exception $e){
                        echo "Error.";
                    }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for($row=1; $row <= $highestRow; $row++ ){
                $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);
                // print_r($rowData); 

                if($row==1){
                    continue;
                }

                // $model = new Excel();

                // $model->Name = $rowData[0][0];
                // $model->Age=$rowData[0][1];
                // $model->CellNumber=$rowData[0][2];

                // $model->save();
            }die();
                    Yii::$app->session->setFlash('importsucceed', "Data imported successfully."); 
                    return $this->redirect(['index']);
            }
        }
    }
}

public function actionSendmail($id, $email){
    $EmailFlag= SiteController::actionResetpwd($id, $email);
    if($EmailFlag == TRUE){
        return $this->redirect('index');
    }
}
    public function actionAllcommunication($id)
    {
        $model = new \backend\modules\user\models\Employeecommunication();

            $qry = \backend\modules\user\models\Employeecommunication::find(['CreatedDate as Date'])->where(['EmployeeID' => $id])->orderBy(['CreatedDate'=>SORT_DESC]);

        $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => $qry->count(),
            ]);

        $query = new Query();
        $query = $query->select(['EC.CreatedDate', 'EC.Tags', 'EC.Details','E.FullName as TalkedWith'])->where(['EC.EmployeeID'=>$id])->from('employeecommunication EC')->leftJoin('employee E','E.EmployeeID = EC.CreatedBy')->orderBy(['CreatedDate'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit);

        $command = $query->createCommand();
        $data = $command->queryAll();
// echo "<pre>"; print_r($data); die();
            return $this->render('allcommunication', [
                'model' => $model,
                'data' => $data,
                'pagination' => $pagination
            ]);
    }

    public function actionCommunication()
    {
        $model = new \backend\modules\user\models\Employeecommunication();

        if ($model->load(Yii::$app->request->post())) {
                $model->EmployeeID =Yii::$app->request->get('id');
                $model->CreatedDate=Date('Y-m-d');
                $model->CreatedBy=Yii::$app->session['EmployeeID'];
                $model->save();
                return $this->redirect(['view','id'=> $model->EmployeeID]);
        }
        return $this->render('communication', [
            'model' => $model,
        ]);
    }

     public function actionGetcommunication()
    {
      $Role = UserController::CheckRole("employee");
        if ($Role == true)
        {
            $EmployeeID = $_POST['EmployeeID'];
            try {
               
                $query = new Query();
                $query = $query->select(['EC.CreatedDate', 'EC.Tags', 'EC.Details','E.FullName as TalkedWith'])->where(['EC.EmployeeID'=>$EmployeeID])->from('employeecommunication EC')->leftJoin('employee E','E.EmployeeID = EC.CreatedBy')->orderBy(['CreatedDate'=>SORT_DESC])->limit(5);

                $command = $query->createCommand();
                $res = $command->queryAll();

        if(sizeof($res) != NULL)
            {
                $html = '';
                foreach ($res as $key => $r) 
                {
                    $html .= '<tr>';
                    $html .= '<td>'.$r['CreatedDate'].'</td>';
                    $html .= '<td>'.$r['Details'].'</td>';
                    $html .= '<td>'.$r['TalkedWith'].'</td>';
                    $html .= '<td>'.$r['Tags'].'</td>';
                    $html .= '</tr>';
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['html' => $html];
            }
            else
            {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['html' => '<tr><td colspan = "3" align = "center">No data available in table</tr>'];  
            }
            
            } catch (Exception $e) {
                return "Error Occured".$e;
            }
        }  
    }

    public function actionTerminatedemp(){
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->searchterminated(Yii::$app->request->queryParams);

        return $this->render('terminatedemp', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRejoin($id){
        $model = Employee::findOne($id);
        $model->IsTerminated = '0';
        $model->IsActive = 1;
        echo "Done";
        if($model->save(false)){   
        return $this->redirect(['terminatedemp']);
        }        
        
    }

    public function actionReference(){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("SELECT EmployeeID , FullName FROM `employee` WHERE IsActive = '1';");
        $result = $connection->createCommand($qry) /*->getRawSql()*/;
        $resultEmp = $result->queryAll();

        $qry = sprintf("SELECT R.`ReferenceID`,R.`EmployeeID`, R.`ReferenceNumber`, R.`Title` AS ReferenceTitle, R.`Details` AS ReferenceDetails, R.`File`, E.`FullName` AS EmployeeName, LI.`Title` AS ReferenceType FROM `reference` R LEFT JOIN employee E ON R.`EmployeeID` = E.`EmployeeID` LEFT JOIN listitems LI ON R.`TypeID` = LI.`ListItemID` WHERE R.`IsActive` = '1';");
        $resultRef = $connection->createCommand($qry) /*->getRawSql()*/;
        $References = $resultRef->queryAll();

        $ReferenceType = Listitems::find()->select('ListItemID, Title, Value')->where(['Type' => 'reference', 'IsActive' => 1])->all();

        return $this->render('reference',[
            'resultEmp' => $resultEmp,
            'ReferenceType' => $ReferenceType,
            'References' => $References
        ]);
    }
    public function actionSavereference(){
        $Role = UserController::CheckRole("employee");
        if ($Role == true)
        {
            $EmployeeID = $_POST['EmpID'];
            $ReferenceType = $_POST['RefTypeID'];
            $ReferenceNumber = $_POST['RefNumber'];
            $ReferenceTitle = $_POST['RefTitle'];
            $ReferenceDetails = $_POST['RefDetails'];
            $FileData = $_FILES["file"]["name"];
            if($FileData != ''){
                $RandomNumber = mt_rand();
                $test = explode('.', $FileData);
                $ext = end($test);
                $filename = str_replace(" ","_",$this->findEmployeeName($EmployeeID))."_".Date('Y-m-d')."_".$RandomNumber.".".$ext;
                $path = Yii::$app->basePath."/../uploads/references/";                
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $path."/".$filename)){
                $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = sprintf("INSERT INTO `reference` (`EmployeeID`, `TypeID`, `ReferenceNumber`, `Title`, `Details`, `File`, `CreatedBy`, `CreatedDate`) VALUES ('%d', '%d', '%d', '%s', '%s', '%s', '%d', '%s')",$EmployeeID, $ReferenceType, $ReferenceNumber, $ReferenceTitle, $ReferenceDetails, $filename, Yii::$app->session['UserID'], Date('Y-m-d'));
                $result = $connection->createCommand($qry)/*->getRawSql()*/;
                $res = $result->execute();
                $return = ($res == TRUE)?'{"result":true,"message":"Saved Successfully."}':'{"result":false,"message":"Error: Not Saved."}';
                return $return;
                }
            }
        }
    }

    public function findEmployeeName($id){
        $query = new Query();
                $connection = Yii::$app->getDb();
                $qry = sprintf("Select FullName from employee where EmployeeID = '%d';", $id);
                $result = $connection->createCommand($qry) /*->getRawSql()*/;
                $res = $result->queryOne();
                if($res == TRUE && $res['FullName'] != ' '){
                    return $res['FullName'];
                }else{
                    return 'NO NAME';
                }
    }

    public function actionDownload($filename){
       $filepath = Yii::$app->basePath."/../uploads/references";
       $findFileIn = $filepath."/".$filename;
       if (file_exists($findFileIn)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($findFileIn).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($findFileIn));
            readfile($findFileIn);
            exit;
        }
    }
    public function actionDeletefile($id, $name){
        $query = new Query();
        $connection = Yii::$app->getDb();
        $qry = sprintf("UPDATE `reference` SET `IsActive` = '0', `IsDeleted` = '1' WHERE `reference`.`ReferenceID` = '%d';",$id);
        $result = $connection->createCommand($qry)/*->getRawSql()*/;
        $res = $result->execute();
        if($res == TRUE){
        $Ignore = array(".","..","Thumbs.db");
            $OriginalFileRoot = Yii::$app->basePath."/../uploads/references";
            $OriginalFile = $name;
            $DestinationRoot = Yii::$app->basePath."/../uploads/references/trash";
                if(!is_dir($DestinationRoot)){
                mkdir($DestinationRoot,0777,true);
                }
            $Date = date('Y-m-d');
            $FileExt = pathinfo($OriginalFile, PATHINFO_EXTENSION); 
            $Filename = basename($OriginalFile, ".".$FileExt); 
            $DestinationFile = $DestinationRoot."/".$Filename.".".$FileExt;
            if(rename($OriginalFileRoot."/".$Filename.".".$FileExt, $DestinationFile)){
                return $this->redirect('reference');
            }           
        }  
    }

    public function actionRefpage($id){
        $query = new Query();
        $model=$query
            ->select(['E.FullName as EmployeeName', 'R.ReferenceNumber','R.Title','R.Details','R.File', 'R.CreatedDate', 'LI.Title as Type'])
            ->from('reference R')
            ->leftJoin('employee E', 'R.EmployeeID = E.EmployeeID')
            ->leftJoin('listitems LI', 'R.TypeID = LI.ListItemID')
            ->orderBy(['R.CreatedDate'=>SORT_DESC])
            ->where(['R.EmployeeID' => $id])
            ->all();
        $Name = self::findEmployeeName($id);
        return $this->render('referenceview',[
            'model' => $model,
            'Name' => $Name
        ]);
    }
}


