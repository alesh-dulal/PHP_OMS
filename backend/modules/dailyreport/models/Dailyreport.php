<?php

namespace backend\modules\dailyreport\models;

use Yii;

/**
 * This is the model class for table "dailyreport".
 *
 * @property integer $DailyReportID
 * @property integer $TotalTask
 * @property string $Report
 * @property string $VerifyUserID
 * @property integer $VerifiedBy
 * @property integer $IsVerified
 * @property integer $SysTotalTask
 * @property string $VerifiedDate
 * @property integer $CreatedBy
 * @property string $CreatedTime
 * @property integer $UpdatedBy
 * @property string $UpdatedTime
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
            [['TotalTask','CreatedBy'], 'required'],
            [['TotalTask','IsVerified', 'SysTotalTask', 'CreatedBy', 'UpdatedBy'], 'integer'],
            [['Report', 'Remarks'], 'string'],
            [['VerifiedDate', 'CreatedTime', 'UpdatedTime','VerifiedBy','VerifyUserID','Report'], 'safe'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DailyReportID' => 'Daily Report ID',
            'TotalTask' => 'Total Task',
            'Report' => 'Report',
            'VerifyUserID' => 'Verify User ID',
            'VerifiedBy' => 'Verified By',
            'IsVerified' => 'Is Verified',
            'SysTotalTask' => 'Sys Total Task',
            'VerifiedDate' => 'Verified Date',
            'CreatedBy' => 'Created By',
            'CreatedTime' => 'Created Time',
            'UpdatedBy' => 'Updated By',
            'UpdatedTime' => 'Updated Time',
            'Remarks' => 'Remarks',
        ];
    }
}
