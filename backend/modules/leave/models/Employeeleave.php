<?php

namespace backend\modules\leave\models;

use Yii;

/**
 * This is the model class for table "employeeleave".
 *
 * @property integer $EmployeeLeaveID
 * @property integer $EmployeeID
 * @property integer $LeaveTypeID
 * @property integer $LeaveID
 * @property string $From
 * @property string $To
 * @property integer $NoOfDays
 * @property string $Reason
 * @property string $File
 * @property integer $IsApproved
 * @property integer $IsRejected
 * @property string $RejectedNote
 * @property integer $ApprovedBy
 * @property string $ApprovedDate
 * @property string $Year
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property integer $UpdatedDate
 * @property integer $IsActive
 * @property integer $IsDeleted
 */
class Employeeleave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employeeleave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'LeaveTypeID', 'LeaveID', 'NoOfDays', 'IsApproved', 'IsRejected', 'ApprovedBy', 'CreatedBy', 'UpdatedBy', 'UpdatedDate', 'IsActive', 'IsDeleted'], 'integer'],
            [['From', 'To', 'ApprovedDate', 'Year', 'CreatedDate'], 'safe'],
            [['Reason','NoOfDays', 'CreatedBy'], 'required'],
            [[ 'RejectedNote'], 'string'],
            [['File'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EmployeeLeaveID' => 'Employee Leave ID',
            'EmployeeID' => 'Employee ID',
            'LeaveTypeID' => 'Leave Type ID',
            'LeaveID' => 'Leave ID',
            'From' => 'From',
            'To' => 'To',
            'NoOfDays' => 'No Of Days',
            'Reason' => 'Reason',
            'File' => 'File',
            'IsApproved' => 'Is Approved',
            'IsRejected' => 'Is Rejected',
            'RejectedNote' => 'Rejected Note',
            'ApprovedBy' => 'Approved By',
            'ApprovedDate' => 'Approved Date',
            'Year' => 'Year',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(\backend\modules\user\models\Employee::className(), ['EmployeeID' => 'EmployeeID']);
    }
    
    
}
