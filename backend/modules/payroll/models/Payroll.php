<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property int $PayrollID
 * @property int $EmployeeID
 * @property int $AllowenceDeductionID
 * @property int $BasicSalaryID
 * @property int $AdvanceID
 * @property int $LeaveDays
 * @property int $LeaveDeduction
 * @property int $ProvidentFundDeduction
 * @property int $CITDeduction
 * @property int $BasicSalaryAmount
 * @property int $SocialSecurityTax
 * @property int $Tax
 * @property int $NetSalary
 * @property string $PayableAmount
 * @property string $Remarks
 */
class Payroll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'AllowenceDeductionID', 'BasicSalaryID', 'AdvanceID'], 'required'],
            [['EmployeeID', 'AllowenceDeductionID', 'BasicSalaryID', 'AdvanceID', 'LeaveDays', 'LeaveDeduction', 'ProvidentFundDeduction', 'CITDeduction', 'BasicSalaryAmount', 'SocialSecurityTax', 'Tax', 'NetSalary'], 'integer'],
            [['PayableAmount', 'Remarks'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PayrollID' => 'Payroll ID',
            'EmployeeID' => 'Employee ID',
            'AllowenceDeductionID' => 'Allowence Deduction ID',
            'BasicSalaryID' => 'Basic Salary ID',
            'AdvanceID' => 'Advance ID',
            'LeaveDays' => 'Leave Days',
            'LeaveDeduction' => 'Leave Deduction',
            'ProvidentFundDeduction' => 'Provident Fund Deduction',
            'CITDeduction' => 'Citdeduction',
            'BasicSalaryAmount' => 'Basic Salary Amount',
            'SocialSecurityTax' => 'Social Security Tax',
            'Tax' => 'Tax',
            'NetSalary' => 'Net Salary',
            'PayableAmount' => 'Payable Amount',
            'Remarks' => 'Remarks',
        ];
    }
}
