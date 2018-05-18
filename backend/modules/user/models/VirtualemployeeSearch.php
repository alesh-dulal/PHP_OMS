<?php

namespace backend\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\models\Virtualemployee;
use backend\modules\user\models\VirtualemployeeSearch;

/**
 * EmployeeSearch represents the model behind the search form about `backend\modules\user\models\Employee`.
 */
class VirtualemployeeSearch extends Virtualemployee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VirtualEmployeeID','Address', 'CreatedBy', 'UpdatedBy', 'IsTerminated', 'IsActive', 'IsDeleted' ,'SupervisorID', 'PerArticle'], 'integer'],
            [['FullName', 'Gender', 'DOB', 'Email', 'CellPhone', 'Address', 'HireDate', 'EmergencyContactName', 'EmergencyContactRelation', 'EmergencyContactCellPhone', 'Image', 'PANFile', 'CreatedDate', 'UpdatedDate', 'PerArticle'], 'safe'],
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
        $query = Virtualemployee::find()->where(['IsActive'=> 1]);

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
            'VirtualEmployeeID' => $this->VirtualEmployeeID,
            'FullName' => $this->FullName,
            'Email' => $this->Email,
            'SupervisorID' => $this->SupervisorID,
            'DOB' => $this->DOB,
            'Gender' => $this->Gender,
            'CellPhone' => $this->CellPhone,
            'Address' => $this->Address,
            'DOB' => $this->DOB,
            'BankAccountNumber' => $this->BankAccountNumber,
            'HireDate' => $this->HireDate,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsTerminated' => $this->IsTerminated,
            'IsDeleted' => $this->IsDeleted,
            'SupervisorID' => $this->SupervisorID,
            'PerArticle' => $this->PerArticle,
            'Image' => $this->Image
        ]);

        $query->andFilterWhere(['like', 'FullName', $this->FullName])
            ->andFilterWhere(['like', 'Gender', $this->Gender])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'CellPhone', $this->CellPhone])
            ->andFilterWhere(['like', 'Address', $this->Address])
            ->andFilterWhere(['like', 'EmergencyContactName', $this->EmergencyContactName])
            ->andFilterWhere(['like', 'EmergencyContactRelation', $this->EmergencyContactRelation])
            ->andFilterWhere(['like', 'EmergencyContactCellPhone', $this->EmergencyContactCellPhone])
            ->andFilterWhere(['like', 'SupervisorID', $this->SupervisorID])
            ->andFilterWhere(['like', 'PerArticle', $this->PerArticle])
            ->andFilterWhere(['like', 'Image', $this->Image])
            ->andFilterWhere(['like', 'BankAccountNumber', $this->BankAccountNumber]);

        return $dataProvider;
    }
}