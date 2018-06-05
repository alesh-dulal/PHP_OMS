<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "reference".
 *
 * @property int $ReferenceID
 * @property int $EmployeeID
 * @property int $TypeID
 * @property string $ReferenceNumber
 * @property int $Title
 * @property string $Details
 * @property string $File
 * @property int $CreatedBy
 * @property string $CreatedDate
 * @property int $UpdatedBy
 * @property string $UpdatedDate
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Reference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'TypeID', 'ReferenceNumber', 'Title', 'Details', 'File', 'CreatedBy'], 'required'],
            [['EmployeeID', 'TypeID', 'Title', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Details'], 'string'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['ReferenceNumber'], 'string', 'max' => 10],
            [['File'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ReferenceID' => 'Reference ID',
            'EmployeeID' => 'Employee ID',
            'TypeID' => 'Type ID',
            'ReferenceNumber' => 'Reference Number',
            'Title' => 'Title',
            'Details' => 'Details',
            'File' => 'File',
            'CreatedBy' => 'Created By',
            'CreatedDate' => 'Created Date',
            'UpdatedBy' => 'Updated By',
            'UpdatedDate' => 'Updated Date',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
