<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "employeecommunication".
 *
 * @property int $UserCommunicationID
 * @property int $EmployeeID
 * @property string $Details
 * @property string $Type
 * @property string $Tags
 * @property int $UserID
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Employeecommunication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employeecommunication';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeID', 'Details', 'Type', 'Tags'], 'required'],
            [['EmployeeID','CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Type'], 'string'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['Details', 'Tags'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'UserCommunicationID' => 'User Communication ID',
            'EmployeeID' => 'Employee ID',
            'Details' => 'Details',
            'Type' => 'Type',
            'Tags' => 'Tags',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
