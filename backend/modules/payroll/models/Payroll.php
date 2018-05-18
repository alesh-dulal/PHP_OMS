<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property int $PayrollID
 * @property int $EmployeeID
 * @property string $FullName
 * @property string $Year
 * @property int $Month
 * @property double $BasicSalary
 * @property double $PF
 * @property double $Gratuity
 * @property double $Allowance
 * @property double $Grade
 * @property double $Incentive
 * @property double $Bonus
 * @property double $TotalAllowance
 * @property double $PFDeduction
 * @property double $CITDeduction
 * @property double $TotalDeduction
 * @property double $Income
 * @property int $AbsentDays
 * @property double $AbsentDeduction
 * @property double $GrossIncome
 * @property double $SST
 * @property double $OtherTAX
 * @property double $NetIncome
 * @property double $AdvanceDeduction
 * @property double $PayableAmount
 * @property string $Remarks
 * @property int $IsProcessed
 * @property int $IsPaid
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
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
            [['EmployeeID', 'FullName', 'Year', 'Month', 'BasicSalary', 'PF', 'Gratuity', 'Grade', 'Incentive', 'Bonus', 'TotalAllowance', 'PFDeduction', 'CITDeduction', 'TotalDeduction', 'Income', 'AbsentDays', 'AbsentDeduction', 'GrossIncome', 'SST', 'OtherTAX', 'NetIncome', 'AdvanceDeduction', 'PayableAmount', 'Remarks'], 'required'],
            [['EmployeeID', 'Month', 'AbsentDays', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Year', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['BasicSalary', 'PF', 'Gratuity', 'Allowance', 'Grade', 'Incentive', 'Bonus', 'TotalAllowance', 'PFDeduction', 'CITDeduction', 'TotalDeduction', 'Income', 'AbsentDeduction', 'GrossIncome', 'SST', 'OtherTAX', 'NetIncome', 'AdvanceDeduction', 'PayableAmount'], 'number'],
            [['FullName', 'Remarks'], 'string', 'max' => 200],
            [['IsProcessed', 'IsPaid'], 'string', 'max' => 1],
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
            'FullName' => 'Full Name',
            'Year' => 'Year',
            'Month' => 'Month',
            'BasicSalary' => 'Basic Salary',
            'PF' => 'Pf',
            'Gratuity' => 'Gratuity',
            'Allowance' => 'Allowance',
            'Grade' => 'Grade',
            'Incentive' => 'Incentive',
            'Bonus' => 'Bonus',
            'TotalAllowance' => 'Total Allowance',
            'PFDeduction' => 'Pfdeduction',
            'CITDeduction' => 'Citdeduction',
            'TotalDeduction' => 'Total Deduction',
            'Income' => 'Income',
            'AbsentDays' => 'Absent Days',
            'AbsentDeduction' => 'Absent Deduction',
            'GrossIncome' => 'Gross Income',
            'SST' => 'Sst',
            'OtherTAX' => 'Other Tax',
            'NetIncome' => 'Net Income',
            'AdvanceDeduction' => 'Advance Deduction',
            'PayableAmount' => 'Payable Amount',
            'Remarks' => 'Remarks',
            'IsProcessed' => 'Is Processed',
            'IsPaid' => 'Is Paid',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
