<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property int $PayrollID
 * @property string $FullName
 * @property string $Year
 * @property int $Month
 * @property int $BasicSalary
 * @property int $PF
 * @property int $Gratuity
 * @property int $CompensationAllowance
 * @property int $Grade
 * @property int $Incentive
 * @property int $Bonus
 * @property int $TotalAllowance
 * @property int $PFDeduction
 * @property int $CITDeduction
 * @property int $TotalDeduction
 * @property int $Income
 * @property int $AbsentDays
 * @property int $AbsentDeduction
 * @property int $GrossIncome
 * @property int $SST
 * @property int $OtherTAX
 * @property int $NetIncome
 * @property int $AdvanceDeduction
 * @property int $PayableAmount
 * @property string $Remarks
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
            [['FullName', 'Year', 'Month', 'BasicSalary', 'PF', 'Gratuity', 'CompensationAllowance', 'Grade', 'Incentive', 'Bonus', 'TotalAllowance', 'PFDeduction', 'CITDeduction', 'TotalDeduction', 'Income', 'AbsentDays', 'AbsentDeduction', 'GrossIncome', 'SST', 'OtherTAX', 'NetIncome', 'AdvanceDeduction', 'PayableAmount', 'Remarks'], 'required'],
            [['Year', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['Month', 'BasicSalary', 'PF', 'Gratuity', 'CompensationAllowance', 'Grade', 'Incentive', 'Bonus', 'TotalAllowance', 'PFDeduction', 'CITDeduction', 'TotalDeduction', 'Income', 'AbsentDays', 'AbsentDeduction', 'GrossIncome', 'SST', 'OtherTAX', 'NetIncome', 'AdvanceDeduction', 'PayableAmount', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['FullName', 'Remarks'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PayrollID' => 'Payroll ID',
            'FullName' => 'Full Name',
            'Year' => 'Year',
            'Month' => 'Month',
            'BasicSalary' => 'Basic Salary',
            'PF' => 'Pf',
            'Gratuity' => 'Gratuity',
            'CompensationAllowance' => 'Compensation Allowance',
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
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
