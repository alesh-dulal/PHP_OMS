<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "advance".
 *
 * @property int $AdvanceID
 * @property int $EmployeeID
 * @property int $Amount
 * @property int $Rule
 * @property int $Month
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
            [['EmployeeID', 'Amount', 'Rule', 'Month', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
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
            'Rule' => 'Rule',
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
