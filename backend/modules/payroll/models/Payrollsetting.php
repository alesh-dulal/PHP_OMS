<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "payrollsetting".
 *
 * @property int $PayrollSettingID
 * @property int $IsAllowence
 * @property string $Title
 * @property int $Amount
 * @property string $Formula
 * @property int $OrderNo
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Payrollsetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payrollsetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IsAllowance', 'Amount', 'OrderNo', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['Title'], 'string', 'max' => 45],
            [['Formula'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PayrollSettingID' => 'Payroll Setting ID',
            'IsAllowance' => 'Is Allowance',
            'Title' => 'Title',
            'Amount' => 'Amount',
            'Formula' => 'Formula',
            'OrderNo' => 'Order No',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
