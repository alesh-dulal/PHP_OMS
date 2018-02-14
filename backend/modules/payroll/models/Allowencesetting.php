<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "allowencesetting".
 *
 * @property int $AllowenceSettingID
 * @property int $IsAllowence
 * @property string $Title
 * @property int $Amount
 * @property string $Formula
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Allowencesetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'allowencesetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AllowenceSettingID', 'IsAllowence', 'Amount', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['Title'], 'string', 'max' => 45],
            [['Formula'], 'string', 'max' => 50],
            [['AllowenceSettingID'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AllowenceSettingID' => 'Allowence Setting ID',
            'IsAllowence' => 'Is Allowence',
            'Title' => 'Title',
            'Amount' => 'Amount',
            'Formula' => 'Formula',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
