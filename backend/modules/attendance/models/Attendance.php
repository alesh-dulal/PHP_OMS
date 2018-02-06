<?php

namespace backend\modules\attendance\models;

use Yii;

/**
 * This is the model class for table "attendance".
 *
 * @property integer $AttendanceID
 * @property integer $EmployeeID
 * @property string $AttnDate
 * @property string $CheckIn
 * @property string $BreakIn
 * @property string $BreakOut
 * @property string $CheckOut
 * @property string $CheckInDiff
 * @property string $CheckOutDiff
 * @property string $WorkedTime
 * @property string $WorkedTimeDiff
 * @property string $CurrentYear
 * @property string $Remarks
 * @property integer $IsActive
 * @property integer $InsertedBy
 * @property string $InsertedDate
 * @property integer $UpdatedBy
 * @property string $UpdatedDate
 */
class Attendance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'AttnDate', 'CheckIn', 'CheckOut', 'CheckInDiff', 'CheckOutDiff', 'WorkedTime', 'WorkedTimeDiff', 'Remarks', 'InsertedBy'], 'required'],
            [['EmployeeID', 'IsActive', 'InsertedBy', 'UpdatedBy'], 'integer'],
            [['AttnDate', 'CheckIn', 'BreakIn', 'BreakOut', 'CheckOut', 'CheckInDiff', 'CheckOutDiff', 'WorkedTime', 'WorkedTimeDiff', 'InsertedDate', 'UpdatedDate'], 'safe'],
            [['Remarks'], 'string'],
            [['CurrentYear'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AttendanceID' => 'Attendance ID',
            'EmployeeID' => 'Employee ID',
            'AttnDate' => 'Attn Date',
            'CheckIn' => 'Check In',
            'BreakIn' => 'Break In',
            'BreakOut' => 'Break Out',
            'CheckOut' => 'Check Out',
            'CheckInDiff' => 'Check In Diff',
            'CheckOutDiff' => 'Check Out Diff',
            'WorkedTime' => 'Worked Time',
            'WorkedTimeDiff' => 'Worked Time Diff',
            'CurrentYear' => 'Current Year',
            'Remarks' => 'Remarks',
            'IsActive' => 'Is Active',
            'InsertedBy' => 'Inserted By',
            'InsertedDate' => 'Inserted Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
        ];
    }
}
