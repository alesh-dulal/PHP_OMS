<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "advance".
 *
 * @property int $AdvanceID
 * @property int $EmployeeID
 * @property int $Amount
 * @property int $Month
 * @property int $Rule
 * @property string $Year
 * @property int $IsPaid
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Advance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'Amount', 'Month', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Year'], 'required'],
            [['Year', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['Rule'], 'string', 'max' => 1],
            [['IsPaid'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AdvanceID' => 'Advance ID',
            'EmployeeID' => 'Employee ID',
            'Amount' => 'Amount',
            'Month' => 'Month',
            'Rule' => 'Rule',
            'Year' => 'Year',
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
