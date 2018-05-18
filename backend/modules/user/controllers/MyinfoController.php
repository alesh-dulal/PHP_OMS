<?php

namespace backend\modules\user\controllers;
use Yii;
use yii\db\Query;

class MyinfoController extends \yii\web\Controller
{
    public function actionMyinfo()
    {
		$query1 = new Query();
		$Result = $query1->select(['E.*','S.FullName SupervisorName' ,'Dep.Title Department','Des.Title Designation','Room.Title Room'])->where(['E.EmployeeID'=>Yii::$app->session['UserID']])->from('employee E')->leftJoin('employee S','E.Supervisor=S.EmployeeID')->leftJoin('listitems Des','Des.ListItemID=E.DesignationID')->leftJoin('listitems Dep','Dep.ListItemID=E.DepartmentID')->leftJoin('listitems Room','Room.ListItemID=E.RoomID')->one();
		
		return $this->render('myinfo',[
			'Result'=> $Result,
		]);
    }

}