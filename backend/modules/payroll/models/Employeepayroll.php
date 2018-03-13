<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "employeepayroll".
 *
 * @property int $EmployeePayrollID
 * @property int $EmployeeID
 * @property int $AllowanceID
 * @property int $IsAllowance
 * @property string $AllowanceTitle
 * @property int $AllowanceAmount
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
            [['EmployeeID', 'AllowanceID', 'IsAllowance', 'AllowanceAmount', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['AllowanceID', 'IsAllowance', 'AllowanceTitle', 'AllowanceAmount'], 'required'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['AllowanceTitle'], 'string', 'max' => 25],
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
            'AllowanceID' => 'Allowance ID',
            'IsAllowance' => 'Is Allowance',
            'AllowanceTitle' => 'Allowance Title',
            'AllowanceAmount' => 'Allowance Amount',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
