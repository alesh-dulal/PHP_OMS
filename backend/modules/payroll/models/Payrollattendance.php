<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "payrollattendance".
 *
 * @property int $PayrollAttendanceID
 * @property int $EmployeeID
 * @property string $Month
 * @property string $Year
 * @property int $AttendanceDays
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Payrollattendance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payrollattendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'AttendanceDays', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Year'], 'required'],
            [['Year', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['Month'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PayrollAttendanceID' => 'Payroll Attendance ID',
            'EmployeeID' => 'Employee ID',
            'Month' => 'Month',
            'Year' => 'Year',
            'AttendanceDays' => 'Attendance Days',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
