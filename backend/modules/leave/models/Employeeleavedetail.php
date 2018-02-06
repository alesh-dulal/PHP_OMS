<?php

namespace backend\modules\leave\models;

use Yii;

/**
 * This is the model class for table "employeeleavedetail".
 *
 * @property integer $EmployeeLeaveDetailID
 * @property integer $EmployeeLeaveID
 * @property integer $EmployeeID
 * @property integer $LeaveTypeID
 * @property integer $LeaveID
 * @property string $Date
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property integer $UpdatedDate
 * @property integer $IsActive
 * @property integer $IsDeleted
 */
class Employeeleavedetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employeeleavedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeLeaveID', 'EmployeeID', 'LeaveTypeID', 'LeaveID', 'CreatedBy', 'UpdatedBy', 'UpdatedDate', 'IsActive', 'IsDeleted'], 'integer'],
            [['Date', 'CreatedDate'], 'safe'],
            [['CreatedBy'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EmployeeLeaveDetailID' => 'Employee Leave Detail ID',
            'EmployeeLeaveID' => 'Employee Leave ID',
            'EmployeeID' => 'Employee ID',
            'LeaveTypeID' => 'Leave Type ID',
            'LeaveID' => 'Leave ID',
            'Date' => 'Date',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
