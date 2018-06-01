<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $EmployeeID
 * @property int $DepartmentID
 * @property int $DesignationID
 * @property int $RoleID
 * @property int $RoomID
 * @property int $BiometricID
 * @property int $DailyTargetTask
 * @property int $ShiftID
 * @property int $UserID
 * @property string $Salutation
 * @property string $FullName
 * @property string $Gender
 * @property string $DOB
 * @property string $Email
 * @property double $Salary
 * @property string $BankAccountNumber
 * @property string $CellPhone
 * @property string $PermanantAddress
 * @property string $TemporaryAddress
 * @property string $HireDate
 * @property string $JoinDate
 * @property string $ReviewDate
 * @property string $NextReviewDate
 * @property int $MaritalStatus
 * @property string $SpouseName
 * @property string $EmergencyContact1Name
 * @property string $EmergencyContact1Relation
 * @property string $EmergencyContact1Cell
 * @property string $EmergencyContact2Name
 * @property string $EmergencyContact2Relation
 * @property string $EmergencyContact2Cell
 * @property string $Ethnicity
 * @property string $Religion
 * @property string $CitizenNumber
 * @property string $CitizenFile
 * @property int $Insurance
 * @property string $CITNumber
 * @property string $CITFile
 * @property string $PANNumber
 * @property string $PANFile
 * @property string $CreatedDate
 * @property int $CreatedBy
 * @property string $UpdatedDate
 * @property int $UpdatedBy
 * @property int $IsActive
 * @property int $IsTerminated
 * @property int $IsDeleted
 * @property string $LoginTime
 * @property string $LogoutTime
 * @property int $Supervisor
 * @property string $Image
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DepartmentID', 'DailyTargetTask', 'DesignationID', 'RoleID', 'RoomID', 'BiometricID', 'ShiftID', 'UserID', 'Insurance', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted', 'Supervisor'], 'integer'],
            [['FullName', 'Email', 'CellPhone', 'PermanantAddress', 'TemporaryAddress', 'HireDate', 'JoinDate'], 'required'],
            [['DOB', 'HireDate', 'JoinDate', 'DailyTargetTask', 'ReviewDate','NextReviewDate', 'CreatedDate', 'UpdatedDate', 'LoginTime', 'LogoutTime'], 'safe'],
            [['Salary'], 'number'],
            [['Salutation', 'Gender'], 'string', 'max' => 10],
            [['FullName', 'Email', 'BankAccountNumber', 'SpouseName', 'EmergencyContact1Name', 'EmergencyContact2Name'], 'string', 'max' => 100],
            [['CellPhone', 'EmergencyContact1Cell', 'EmergencyContact2Cell'], 'string', 'max' => 15],
            [['PermanantAddress', 'TemporaryAddress', 'EmergencyContact1Relation', 'EmergencyContact2Relation', 'CitizenNumber', 'CITNumber', 'PANNumber'], 'string', 'max' => 50],
            [['MaritalStatus', 'IsTerminated'], 'string', 'max' => 4],
            [['Ethnicity', 'Religion'], 'string', 'max' => 20],
            [['CitizenFile', 'CITFile', 'PANFile', 'Image'], 'string', 'max' => 200],
            [['Email'], 'unique'],
            [['UserID'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EmployeeID' => 'Employee ID',
            'DepartmentID' => 'Department ID',
            'DesignationID' => 'Designation ID',
            'RoleID' => 'Role ID',
            'RoomID' => 'Room ID',
            'BiometricID' => 'Biometric ID',
            'ShiftID' => 'Shift ID',
            'UserID' => 'User ID',
            'Salutation' => 'Salutation',
            'FullName' => 'Full Name',
            'Gender' => 'Gender',
            'DOB' => 'Dob',
            'Email' => 'Email',
            'Salary' => 'Salary',
            'BankAccountNumber' => 'Bank Account Number',
            'CellPhone' => 'Cell Phone',
            'PermanantAddress' => 'Permanant Address',
            'TemporaryAddress' => 'Temporary Address',
            'HireDate' => 'Hire Date',
            'JoinDate' => 'Join Date',
            'ReviewDate' => 'Review Date',
            'NextReviewDate' => 'Next Review Date',
            'MaritalStatus' => 'Marital Status',
            'SpouseName' => 'Spouse Name',
            'EmergencyContact1Name' => 'Emergency Contact1 Name',
            'EmergencyContact1Relation' => 'Emergency Contact1 Relation',
            'EmergencyContact1Cell' => 'Emergency Contact1 Cell',
            'EmergencyContact2Name' => 'Emergency Contact2 Name',
            'EmergencyContact2Relation' => 'Emergency Contact2 Relation',
            'EmergencyContact2Cell' => 'Emergency Contact2 Cell',
            'Ethnicity' => 'Ethnicity',
            'Religion' => 'Religion',
            'CitizenNumber' => 'Citizen Number',
            'CitizenFile' => 'Citizen File',
            'Insurance' => 'Insurance',
            'CITNumber' => 'Citnumber',
            'CITFile' => 'Citfile',
            'PANNumber' => 'Pannumber',
            'PANFile' => 'Panfile',
            'CreatedDate' => 'Created Date',
            'CreatedBy' => 'Created By',
            'UpdatedDate' => 'Updated Date',
            'UpdatedBy' => 'Updated By',
            'IsActive' => 'Is Active',
            'IsTerminated' => 'Is Terminated',
            'IsDeleted' => 'Is Deleted',
            'LoginTime' => 'Login Time',
            'LogoutTime' => 'Logout Time',
            'Supervisor' => 'Supervisor',
            'Image' => 'Image',
            'DailyTargetTask' => 'Daily Target Task',
        ];
    }
}
