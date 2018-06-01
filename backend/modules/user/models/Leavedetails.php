<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "leavedetails".
 *
 * @property int $LeaveDetailID
 * @property int $EmployeeID
 * @property int $LeaveTypeID
 * @property int $Month
 * @property string $Year
 * @property int $Accrue
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Leavedetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leavedetails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'LeaveTypeID', 'Month', 'Accrue', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Year'], 'required'],
            [['Year', 'CreatedDate', 'UpdatedDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LeaveDetailID' => 'Leave Detail ID',
            'EmployeeID' => 'Employee ID',
            'LeaveTypeID' => 'Leave Type ID',
            'Month' => 'Month',
            'Year' => 'Year',
            'Accrue' => 'Accrue',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
