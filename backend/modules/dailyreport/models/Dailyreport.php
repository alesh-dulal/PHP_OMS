<?php

namespace backend\modules\dailyreport\models;

use Yii;

/**
 * This is the model class for table "dailyreport".
 *
 * @property int $DailyReportID
 * @property int $UserID
 * @property string $Day
 * @property int $LoginTime
 * @property string $LoginIP
 * @property int $ExitTime
 * @property string $ExitIP
 * @property string $Report
 * @property string $ReportDate
 * @property int $TotalTask
 * @property string $LoginLate
 * @property string $ExitFast
 * @property int $StayTime
 * @property int $IsSubmitted
 * @property int $IsPending
 * @property int $IsAccepted
 * @property int $VerifiedBy
 * @property string $VerifiedDate
 * @property string $HostName
 * @property string $HostIP
 * @property int $SystemGeneratedTask
 * @property int $IsActive
 * @property int $IsDeleted
 * @property int $CreatedBy
 * @property string $CreatedDate
 * @property int $UpdatedBy
 * @property string $UpdatedDate
 * @property string $Remarks
 */
class Dailyreport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dailyreport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserID', 'Day', 'LoginTime', 'LoginIP', 'HostName', 'HostIP', 'CreatedBy'], 'required'],
            [['UserID', 'LoginTime', 'ExitTime', 'TotalTask', 'StayTime', 'VerifiedBy', 'SystemGeneratedTask', 'CreatedBy', 'UpdatedBy'], 'integer'],
            [['Day', 'ReportDate', 'VerifiedDate', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['Report', 'LoginLate', 'ExitFast', 'Remarks'], 'string'],
            [['LoginIP', 'ExitIP'], 'string', 'max' => 50],
            [['IsSubmitted', 'IsPending', 'IsAccepted', 'IsDeleted'], 'string', 'max' => 1],
            [['HostName', 'HostIP'], 'string', 'max' => 100],
            [['IsActive'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DailyReportID' => 'Daily Report ID',
            'UserID' => 'User ID',
            'Day' => 'Day',
            'LoginTime' => 'Login Time',
            'LoginIP' => 'Login Ip',
            'ExitTime' => 'Exit Time',
            'ExitIP' => 'Exit Ip',
            'Report' => 'Report',
            'ReportDate' => 'Report Date',
            'TotalTask' => 'Total Task',
            'LoginLate' => 'Login Late',
            'ExitFast' => 'Exit Fast',
            'StayTime' => 'Stay Time',
            'IsSubmitted' => 'Is Submitted',
            'IsPending' => 'Is Pending',
            'IsAccepted' => 'Is Accepted',
            'VerifiedBy' => 'Verified By',
            'VerifiedDate' => 'Verified Date',
            'HostName' => 'Host Name',
            'HostIP' => 'Host Ip',
            'SystemGeneratedTask' => 'System Generated Task',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'Remarks' => 'Remarks',
        ];
    }
}
