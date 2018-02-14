<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "employeepayroll".
 *
 * @property int $EmployeePayrollID
 * @property int $EmployeeID
 * @property int $AllowenceID
 * @property int $Amount
 * @property string $Formula
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
            [['EmployeeID', 'AllowenceID', 'Amount'], 'integer'],
            [['Formula'], 'string', 'max' => 45],
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
            'AllowenceID' => 'Allowence ID',
            'Amount' => 'Amount',
            'Formula' => 'Formula',
        ];
    }
}
