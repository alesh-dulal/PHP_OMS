<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "designationsalary".
 *
 * @property int $DesignationSalaryID
 * @property int $DesignationID
 * @property int $SalaryAmount
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Designationsalary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'designationsalary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DesignationID', 'SalaryAmount', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DesignationSalaryID' => 'Designation Salary ID',
            'DesignationID' => 'Designation ID',
            'SalaryAmount' => 'Salary Amount',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
