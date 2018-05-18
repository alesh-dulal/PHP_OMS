<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "salarycalculationamendment".
 *
 * @property int $SalaryAmendmentID
 * @property string $AmendmentName
 * @property double $TotalWorkingHourPerDay
 * @property double $MaximumPaidLeaveDays
 * @property double $MaximumOTHoursPerDay
 * @property double $SalaryCalcPercentOfOTHours
 * @property double $SalaryDeductionOfLessHours
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Salarycalculationamendment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salarycalculationamendment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AmendmentName', 'TotalWorkingHourPerDay', 'MaximumPaidLeaveDays', 'MaximumOTHoursPerDay', 'SalaryCalcPercentOfOTHours', 'SalaryDeductionOfLessHours'], 'required'],
            [['TotalWorkingHourPerDay', 'MaximumPaidLeaveDays', 'MaximumOTHoursPerDay', 'SalaryCalcPercentOfOTHours', 'SalaryDeductionOfLessHours'], 'number'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['AmendmentName'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SalaryAmendmentID' => 'Salary Amendment ID',
            'AmendmentName' => 'Amendment Name',
            'TotalWorkingHourPerDay' => 'Total Working Hour Per Day',
            'MaximumPaidLeaveDays' => 'Maximum Paid Leave Days',
            'MaximumOTHoursPerDay' => 'Maximum Othours Per Day',
            'SalaryCalcPercentOfOTHours' => 'Salary Calc Percent Of Othours',
            'SalaryDeductionOfLessHours' => 'Salary Deduction Of Less Hours',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
