<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

use backend\modules\user\models\Employee;
use backend\modules\user\models\User;
use yii\db\Query;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['changepassword','login','forgetpassword','resetpwd','resetpassword','saveresetpassword', 'sessionout',  'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('dashboard/default');
    }
    
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // keep session            
           // print_r(Yii::$app->user->identity->UserId); die();
       $query = new Query();
       $connection = Yii::$app->getDb();
       $command = $connection->createCommand( "
        select R.Name As RoleName, U.UserName,E.EmployeeID,U.UserId,E.FullName,R.RoleID,R.MenuID,E.Supervisor,S.FullName as SupervisorName from user U left join employee E on E.UserID=U.UserId left join role R on R.RoleID=E.RoleID left join employee S on E.Supervisor=S.EmployeeID where U.UserId = ".Yii::$app->user->identity->UserId);

        $Result = $command->queryOne();
            $UserID = $Result['UserId'];
            $UserName = $Result['UserName'];
            $FullName = $Result['FullName'];
            $EmployeeID = $Result['EmployeeID'];
            $SupervisorID = $Result['Supervisor'];
            $SupervisorName = $Result['SupervisorName'];   
            $RoleID = $Result['RoleID'];
            $MenuID = $Result['MenuID'];
            $RoleName = $Result['RoleName'];

            // print_r($MenuID); die();
            
            $session = Yii::$app->session;
            $session->set('UserID',$UserID);
            $session->set('UserName',$UserName);
            $session->set('FullName',$FullName);
            $session->set('EmployeeID',$EmployeeID);
            $session->set('RoleID',$RoleID );
            $session->set('SupervisorID', $SupervisorID);
            $session->set('SupervisorName',$SupervisorName);
            $session->set('Menus',$MenuID );
            $session->set('Role',$RoleName );

            $this->SaveLog('Login', gethostname().",". $this->gethostIP());
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $this->saveLog('Logout', gethostname().",". $this->gethostIP());
        Yii::$app->user->logout();
        
        return $this->goHome();
    }    
    
    public function actionForgetpassword()
    {
        if (isset($_POST["reset"])){
            $Email = $_POST['username'];
            $query = new Query();
            $GetUser = $query->select(['E.FullName','U.UserId', 'U.UserName'])->from('user U')->leftjoin('employee E', 'E.Email = U.UserName')->where(['UserName' => $Email])->andWhere(['U.IsActive' => '1'])->one();
            if ($GetUser == true) {
            //call ResetPassword here
                // $this->ResetPassword($GetUser['UserID'], $Email);

                    if ($this->actionResetpwd($GetUser['UserId'], $Email) === true){
                        Yii::$app->session->setFlash('EmailSent', "Email Sent Successfully. Please Check Your Email. ");
                        $model = new LoginForm();
                         return $this->redirect('login');
                    }else{

                    }

            } else {
               Yii::$app->session->setFlash('InvalidAttemptPasswordReset', "Enter Email Carefully. ");
                    return $this->render('forgetpassword');
            }    
        }
        else{
            return $this->render('forgetpassword');
        }
    }

    public function actionResetpwd($userID=null, $Email=null)
    {
        if(isset($_POST['employeeid']) && isset($_POST['employeeemail'])){
            $mail = $_POST['employeeemail'];
            $id = $_POST['employeeid'];
            $userID = $id;
            $Email = $mail;
        }

        $resetLink =  $this->GetPasswordResetLinkLink($userID);
        $HTTP = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'? "https://" : "http://";
        $URL = $HTTP . $_SERVER["SERVER_NAME"] . Yii::$app->urlManager->baseUrl;
        $Message = $URL."/site/resetpassword?key=".$resetLink;    
        $Subject = "";
        $sendMail = Yii::$app->email->sendemail($Email, $Message, $Subject);
        return true;
    }

    protected function GetPasswordResetLinkLink($userID)
    {
        $passwordKey=Yii::$app->crypto->RandomKey(112);
        $connection = Yii::$app->getDb();

        $command = $connection->createCommand("
            update user set IsPasswordReset='1', PasswordKey ='".$passwordKey."' WHERE userid='".$userID."';
                 ");
        $command->execute();
        $current=date('Y-m-d H:i:s');
        return  urlencode(Yii::$app->crypto->Encode($passwordKey.'||'.$userID.'||'.$current));
    }

    public function actionResetpassword($key)
    { 
      $data= Yii::$app->crypto->Decode($key);
      $listData= explode("||", $data);
        if(sizeof($listData)==3){
            $dt = \DateTime::createFromFormat('Y-m-d H:i:s',$listData[2]);
            $dt->modify('+2 day');
            $current=date('Y-m-d H:i:s');
            if($current<$dt)
            {
                $Key = $listData[0];
                $userID = $listData[1];
                $queryS = (new \yii\db\Query())
                ->select(['count(*) count'])
                ->from('user')
                ->where(['UserId' => $userID])
                ->andWhere(['PasswordKey'=> $Key])
                ->andWhere(['IsPasswordReset' => 1])
                ->one();
                    if($queryS['count'] == 1){
                        return $this->render('resetpassword');     
                    }
            }
        }
    } 

    public function actionSaveresetpassword(){
            if (isset($_POST["change"])){
                $Hidden = $_POST['UserID'];
                    $data= Yii::$app->crypto->Decode($Hidden);
                    $listData= explode("||", $data);
                    $userID = $listData[1];
                    $Password1 = $_POST['password1'];
                    $Password2 = $_POST['password2'];

                    if($Password1!= "" && $Password2 != "" && $Password1 == $Password2){
                        $RealPassword = md5($Password1);
                        $queryS = "update user set Password = '%s', IsPasswordReset=0, PasswordKey='' where UserID = '%d'";
                        $queryQ = sprintf($queryS, $RealPassword, $userID);
                        $connection = Yii::$app->getDb();
                        $queryCheck = $connection->createCommand($queryQ);
                        $count = $queryCheck->execute();

                        Yii::$app->session->setFlash('resetPasswordSucceed', "Password successfully reset.");
                        return $this->redirect('login');
                    }else{
                        Yii::$app->session->setFlash('ConfirmPasswordMismatching', "Password != ConfirmPassword.");
                        return $this->render('resetpassword');
                    }
            }             
    }

    public function gethostIP(){
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        $result = "Unknown";
            if (filter_var($client, FILTER_VALIDATE_IP)) {
                $ip = $client;
            } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
                $ip = $forward;
            } else {
                $ip = $remote;
            }
        return $ip;
    }

    public function actionChangepassword(){
        if (isset($_POST["change"])){
            $UserID = Yii::$app->session['UserID'];
            $OldPassword = $_POST['oldpassword'];
            $EncOldPassword = md5($OldPassword);/*Encrypted Old Password*/

            $NewPassword = $_POST['newpassword'];
            $ConfirmPassword = $_POST['confirmpassword'];

            $queryS = (new \yii\db\Query())
                ->select(['count(*) count'])
                ->from('user')
                ->where(['UserId' => $UserID])
                ->andWhere(['Password'=> $EncOldPassword])
                ->one();
            if($queryS['count'] == 1){
                 if ($NewPassword == $ConfirmPassword) {
                        //query for changing password
                        $query = "update user set Password = '%s' where UserID = '%d'";
                        $querychange = sprintf($query, md5($NewPassword) , $UserID);
                        $connection = Yii::$app->getDb();
                        $queryCommand = $connection->createCommand($querychange);
                        $Updated = $queryCommand->execute();

                        if($Updated){
                            $Success="Changed Successfully";
                            $redirect='<meta http-equiv="refresh" content="3;url=/oms" />';
                         return $this->render('changepassword',[
                            'Success'=> $Success,
                            'redirect'=> $redirect,
                         ]);
                        } 

                 } else {
                     $Error="Confirm Password Not Match";
                  return $this->render('changepassword',[
                        'Error'=> $Error,
                    ]);
                 }     
            }else{
                 $Error="Incorrect old password";
                  return $this->render('changepassword',[
                    'Error'=> $Error,
                ]);
            }
        } else {
            return $this->render('changepassword');
        }
        
           
    }

}

