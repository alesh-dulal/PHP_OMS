<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "employeepayroll".
 *
 * @property int $EmployeePayrollID
 * @property int $EmployeeID
 * @property int $BasicSalary
 * @property int $IsAllowance
 * @property string $AllowanceTitle
 * @property int $AllowanceAmount
 * @property int $AllowanceTotal
 * @property int $TotalSalary
 * @property string $Month
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Employeepayroll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employeepayroll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'BasicSalary', 'IsAllowance', 'AllowanceAmount', 'AllowanceTotal', 'TotalSalary', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['BasicSalary', 'IsAllowance', 'AllowanceTitle', 'AllowanceAmount', 'AllowanceTotal', 'TotalSalary', 'Month'], 'required'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['AllowanceTitle'], 'string', 'max' => 25],
            [['Month'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EmployeePayrollID' => 'Employee Payroll ID',
            'EmployeeID' => 'Employee ID',
            'BasicSalary' => 'Basic Salary',
            'IsAllowance' => 'Is Allowance',
            'AllowanceTitle' => 'Allowance Title',
            'AllowanceAmount' => 'Allowance Amount',
            'AllowanceTotal' => 'Allowance Total',
            'TotalSalary' => 'Total Salary',
            'Month' => 'Month',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
