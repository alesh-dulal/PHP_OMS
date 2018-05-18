<?php

namespace backend\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `backend\modules\user\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Supervisor', 'EmployeeID', 'DepartmentID', 'DesignationID', 'RoleID', 'RoomID', 'BiometricID', 'ShiftID', 'UserID', 'TemporaryAddress', 'MaritalStatus', 'CitizenNumber', 'Insurance', 'CITNumber', 'PANNumber', 'CreatedBy', 'UpdatedBy', 'IsTerminated', 'IsActive', 'IsDeleted'], 'integer'],
            [['Salutation', 'FullName', 'Gender', 'DOB', 'Email', 'CellPhone', 'PermanantAddress', 'HireDate', 'JoinDate', 'ReviewDate','NextReviewDate', 'SpouseName', 'EmergencyContact1Name', 'EmergencyContact1Relation', 'EmergencyContact1Cell', 'EmergencyContact2Name', 'EmergencyContact2Relation', 'EmergencyContact2Cell', 'Ethnicity', 'Religion', 'CitizenFile', 'CITFile','Image', 'PANFile', 'CreatedDate', 'UpdatedDate', 'Salary', 'LogoutTime', 'LoginTime','BankAccountNumber'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Employee::find()->where(['IsActive'=> 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query ->andFilterWhere([
            'EmployeeID' => $this->EmployeeID,
            'DepartmentID' => $this->DepartmentID,
            'DesignationID' => $this->DesignationID,
            'RoleID' => $this->RoleID,
            'RoomID' => $this->RoomID,
            'BiometricID' => $this->BiometricID,
            'ShiftID' => $this->ShiftID,
            'UserID' => $this->UserID,
            'DOB' => $this->DOB,
            'TemporaryAddress' => $this->TemporaryAddress,
            'HireDate' => $this->HireDate,
            'JoinDate' => $this->JoinDate,
            'ReviewDate' => $this->ReviewDate,
            'NextReviewDate' => $this->NextReviewDate,
            'MaritalStatus' => $this->MaritalStatus,
            'CitizenNumber' => $this->CitizenNumber,
            'Insurance' => $this->Insurance,
            'CITNumber' => $this->CITNumber,
            'PANNumber' => $this->PANNumber,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsTerminated' => $this->IsTerminated,
            'IsDeleted' => $this->IsDeleted,
            'Supervisor' => $this->Supervisor,
            'Salary' => $this->Salary,
            'LoginTime' => $this->LoginTime,
            'LogoutTime' => $this->LogoutTime,
            'Image' => $this->Image,
            'BankAccountNumber' => $this->BankAccountNumber,
        ]);

        $query->andFilterWhere(['like', 'Salutation', $this->Salutation])
            ->andFilterWhere(['like', 'FullName', $this->FullName])
            ->andFilterWhere(['like', 'Gender', $this->Gender])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'CellPhone', $this->CellPhone])
            ->andFilterWhere(['like', 'PermanantAddress', $this->PermanantAddress])
            ->andFilterWhere(['like', 'SpouseName', $this->SpouseName])
            ->andFilterWhere(['like', 'EmergencyContact1Name', $this->EmergencyContact1Name])
            ->andFilterWhere(['like', 'EmergencyContact1Relation', $this->EmergencyContact1Relation])
            ->andFilterWhere(['like', 'EmergencyContact1Cell', $this->EmergencyContact1Cell])
            ->andFilterWhere(['like', 'EmergencyContact2Name', $this->EmergencyContact2Name])
            ->andFilterWhere(['like', 'EmergencyContact2Relation', $this->EmergencyContact2Relation])
            ->andFilterWhere(['like', 'EmergencyContact2Cell', $this->EmergencyContact2Cell])
            ->andFilterWhere(['like', 'Ethnicity', $this->Ethnicity])
            ->andFilterWhere(['like', 'Religion', $this->Religion])
            ->andFilterWhere(['like', 'CitizenFile', $this->CitizenFile])
            ->andFilterWhere(['like', 'CITFile', $this->CITFile])
            ->andFilterWhere(['like', 'PANFile', $this->PANFile])
            ->andFilterWhere(['like', 'Supervisor', $this->Supervisor])
            ->andFilterWhere(['like', 'Salary', $this->Salary])
            ->andFilterWhere(['like', 'LoginTime', $this->LoginTime])
            ->andFilterWhere(['like', 'LogoutTime', $this->LogoutTime])
            ->andFilterWhere(['like', 'Image', $this->Image])
            ->andFilterWhere(['like', 'BankAccountNumber', $this->BankAccountNumber]);

        return $dataProvider;
    }




    public function searchterminated($params)
    {
        $queryT = Employee::find()->where(['IsTerminated' => 1]);

        // add conditions that should always apply here

        $dataProviderT = new ActiveDataProvider([
            'query' => $queryT,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProviderT;
        }

        // grid filtering conditions
        $queryT ->andFilterWhere([
            'EmployeeID' => $this->EmployeeID,
            'DepartmentID' => $this->DepartmentID,
            'DesignationID' => $this->DesignationID,
            'RoleID' => $this->RoleID,
            'RoomID' => $this->RoomID,
            'BiometricID' => $this->BiometricID,
            'ShiftID' => $this->ShiftID,
            'UserID' => $this->UserID,
            'DOB' => $this->DOB,
            'TemporaryAddress' => $this->TemporaryAddress,
            'HireDate' => $this->HireDate,
            'JoinDate' => $this->JoinDate,
            'ReviewDate' => $this->ReviewDate,
            'ReviewDate' => $this->ReviewDate,
            'NextReviewDate' => $this->NextReviewDate,
            'MaritalStatus' => $this->MaritalStatus,
            'CitizenNumber' => $this->CitizenNumber,
            'Insurance' => $this->Insurance,
            'CITNumber' => $this->CITNumber,
            'PANNumber' => $this->PANNumber,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsTerminated' => $this->IsTerminated,
            'IsDeleted' => $this->IsDeleted,
            'Supervisor' => $this->Supervisor,
            'Salary' => $this->Salary,
            'LoginTime' => $this->LoginTime,
            'LogoutTime' => $this->LogoutTime,
            'Image' => $this->Image,
            'BankAccountNumber' => $this->BankAccountNumber,
        ]);

        $queryT->andFilterWhere(['like', 'Salutation', $this->Salutation])
            ->andFilterWhere(['like', 'FullName', $this->FullName])
            ->andFilterWhere(['like', 'Gender', $this->Gender])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'CellPhone', $this->CellPhone])
            ->andFilterWhere(['like', 'PermanantAddress', $this->PermanantAddress])
            ->andFilterWhere(['like', 'SpouseName', $this->SpouseName])
            ->andFilterWhere(['like', 'EmergencyContact1Name', $this->EmergencyContact1Name])
            ->andFilterWhere(['like', 'EmergencyContact1Relation', $this->EmergencyContact1Relation])
            ->andFilterWhere(['like', 'EmergencyContact1Cell', $this->EmergencyContact1Cell])
            ->andFilterWhere(['like', 'EmergencyContact2Name', $this->EmergencyContact2Name])
            ->andFilterWhere(['like', 'EmergencyContact2Relation', $this->EmergencyContact2Relation])
            ->andFilterWhere(['like', 'EmergencyContact2Cell', $this->EmergencyContact2Cell])
            ->andFilterWhere(['like', 'Ethnicity', $this->Ethnicity])
            ->andFilterWhere(['like', 'Religion', $this->Religion])
            ->andFilterWhere(['like', 'CitizenFile', $this->CitizenFile])
            ->andFilterWhere(['like', 'CITFile', $this->CITFile])
            ->andFilterWhere(['like', 'PANFile', $this->PANFile])
            ->andFilterWhere(['like', 'Supervisor', $this->Supervisor])
            ->andFilterWhere(['like', 'Salary', $this->Salary])
            ->andFilterWhere(['like', 'LoginTime', $this->LoginTime])
            ->andFilterWhere(['like', 'LogoutTime', $this->LogoutTime])
            ->andFilterWhere(['like', 'Image', $this->Image])
            ->andFilterWhere(['like', 'BankAccountNumber', $this->BankAccountNumber]);

        return $dataProviderT;
    }
}
