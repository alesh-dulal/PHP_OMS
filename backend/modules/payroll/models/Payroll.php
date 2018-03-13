<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property int $PayrollID
 * @property string $EmployeeName
 * @property int $BasicSalary
 * @property int $PF
 * @property int $Bonus
 * @property int $TotalAllowane
 * @property int $AbsentDays
 * @property int $AbsentDeduction
 * @property int $PFDeduction
 * @property int $SST
 * @property int $OtherTax
 * @property int $Total
 * @property int $Deduction
 * @property int $NetSalary
 * @property int $AdvanceDeduction
 * @property int $PayableAmount
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
            [['EmployeeName', 'BasicSalary', 'PF', 'Bonus', 'TotalAllowane', 'AbsentDays', 'AbsentDeduction', 'PFDeduction', 'SST', 'OtherTax', 'Total', 'Deduction', 'NetSalary', 'AdvanceDeduction', 'PayableAmount'], 'required'],
            [['BasicSalary', 'PF', 'Bonus', 'TotalAllowane', 'AbsentDays', 'AbsentDeduction', 'PFDeduction', 'SST', 'OtherTax', 'Total', 'Deduction', 'NetSalary', 'AdvanceDeduction', 'PayableAmount'], 'integer'],
            [['EmployeeName', 'Remarks'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    
    public function attributeLabels()
    {
        return [
            'PayrollID' => 'Payroll ID',
            'EmployeeName' => 'Employee Name',
            'BasicSalary' => 'Basic Salary',
            'PF' => 'Pf',
            'Bonus' => 'Bonus',
            'TotalAllowane' => 'Total Allowane',
            'AbsentDays' => 'Absent Days',
            'AbsentDeduction' => 'Absent Deduction',
            'PFDeduction' => 'Pfdeduction',
            'SST' => 'Sst',
            'OtherTax' => 'Other Tax',
            'Total' => 'Total',
            'Deduction' => 'Deduction',
            'NetSalary' => 'Net Salary',
            'AdvanceDeduction' => 'Advance Deduction',
            'PayableAmount' => 'Payable Amount',
            'Remarks' => 'Remarks',
        ];
    }
}
