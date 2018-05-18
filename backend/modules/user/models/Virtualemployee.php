<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "virtualemployee".
 *
 * @property int $VirtualEmployeeID
 * @property string $FullName
 * @property string $Email
 * @property string $DOB
 * @property string $Gender
 * @property string $CellPhone
 * @property string $Address
 * @property string $HireDate
 * @property string $EmergencyContactName
 * @property string $EmergencyContactRelation
 * @property string $EmergencyContactCellPhone
 * @property int $PerArticle
 * @property string $BankAccountNumber
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsTerminated
 * @property int $IsActive
 * @property int $IsDeleted
 */
class Virtualemployee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'virtualemployee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FullName', 'Email', 'DOB', 'CellPhone', 'HireDate', 'EmergencyContactName', 'EmergencyContactRelation','SupervisorID', 'EmergencyContactCellPhone', 'PerArticle', 'BankAccountNumber'], 'required'],
            [['DOB', 'HireDate', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['Gender'], 'string'],
            [['PerArticle', 'CreatedBy', 'UpdatedBy', 'IsTerminated', 'IsActive','SupervisorID', 'IsDeleted'], 'integer'],
            [['FullName', 'Email', 'Address', 'EmergencyContactName', 'EmergencyContactRelation', 'BankAccountNumber'], 'string', 'max' => 50],
            [['CellPhone', 'EmergencyContactCellPhone'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VirtualEmployeeID' => 'Virtual Employee ID',
            'SupervisorID' => 'Supervisor ID',
            'FullName' => 'Full Name',
            'Email' => 'Email',
            'DOB' => 'Dob',
            'Gender' => 'Gender',
            'CellPhone' => 'Cell Phone',
            'Address' => 'Address',
            'HireDate' => 'Hire Date',
            'EmergencyContactName' => 'Emergency Contact Name',
            'EmergencyContactRelation' => 'Emergency Contact Relation',
            'EmergencyContactCellPhone' => 'Emergency Contact Cell Phone',
            'PerArticle' => 'Per Article',
            'BankAccountNumber' => 'Bank Account Number',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsTerminated' => 'Is Terminated',
            'IsActive' => 'Is Active',
            'IsDeleted' => 'Is Deleted',
        ];
    }
}
