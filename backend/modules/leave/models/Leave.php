<?php

namespace backend\modules\leave\models;

use Yii;

/**
 * This is the model class for table "leave".
 *
 * @property integer $LeaveID
 * @property integer $EmployeeID
 * @property integer $LeaveTypeID
 * @property integer $Earned
 * @property integer $Balance
 * @property integer $CreatedBy
 * @property string $CreatedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 * @property integer $IsActive
 * @property integer $IsDeleted
 * @property integer $Year
 */
class Leave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'LeaveTypeID', 'CreatedBy'], 'required'],
            [['EmployeeID', 'LeaveTypeID', 'Earned', 'Balance', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted', 'Year'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LeaveID' => 'Leave ID',
            'EmployeeID' => 'Employee ID',
            'LeaveTypeID' => 'Leave Type ID',
            'Earned' => 'Earned',
            'Balance' => 'Balance',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
            'Year' => 'Year',
        ];
    }
}
