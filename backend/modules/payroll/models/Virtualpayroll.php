<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "virtualpayroll".
 *
 * @property int $VirtualPayrollID
 * @property int $VirtualEmployeeID
 * @property string $VirtualEmployeeName
 * @property string $VirtualEmployeeEmail
 * @property int $Month
 * @property string $Year
 * @property double $TotalNoArticle
 * @property double $Income
 * @property double $Bonus
 * @property double $NetIncome
 * @property double $SST
 * @property double $OtherTAX
 * @property double $PayableAmount
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsProcessed
 * @property int $IsPaid
 * @property int $IsActive
 * @property int $IsDeleted
 * @property string $Remarks
 */
class Virtualpayroll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'virtualpayroll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VirtualEmployeeID', 'VirtualEmployeeName', 'VirtualEmployeeEmail', 'Month', 'Year', 'TotalNoArticle', 'Income', 'Bonus', 'NetIncome', 'SST', 'OtherTAX', 'PayableAmount'], 'required'],
            [['VirtualEmployeeID', 'Month', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Year', 'CreatedDate', 'UpdatedDate', 'Remarks'], 'safe'],
            [['TotalNoArticle', 'Income', 'Bonus', 'NetIncome', 'SST', 'OtherTAX', 'PayableAmount'], 'number'],
            [['Remarks'], 'string'],
            [['VirtualEmployeeName', 'VirtualEmployeeEmail'], 'string', 'max' => 100],
            [['IsProcessed', 'IsPaid'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VirtualPayrollID' => 'Virtual Payroll ID',
            'VirtualEmployeeID' => 'Virtual Employee ID',
            'VirtualEmployeeName' => 'Virtual Employee Name',
            'VirtualEmployeeEmail' => 'Virtual Employee Email',
            'Month' => 'Month',
            'Year' => 'Year',
            'TotalNoArticle' => 'Total No Article',
            'Income' => 'Income',
            'Bonus' => 'Bonus',
            'NetIncome' => 'Net Income',
            'SST' => 'Sst',
            'OtherTAX' => 'Other Tax',
            'PayableAmount' => 'Payable Amount',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsProcessed' => 'Is Processed',
            'IsPaid' => 'Is Paid',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
            'Remarks' => 'Remarks',
        ];
    }
}
