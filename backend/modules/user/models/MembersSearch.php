<?php

namespace backend\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\models\Members;

/**
 * MembersSearch represents the model behind the search form about `backend\modules\user\models\Members`.
 */
class MembersSearch extends Members
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MemberID', 'CreatedBy', 'UpdatedBy'], 'integer'],
            [['FullName', 'Address', 'CellPhone', 'Email', 'Remarks', 'Type', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Members::find();

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
        $query->andFilterWhere([
            'MemberID' => $this->MemberID,
            'CreatedBy' => $this->CreatedBy,
            'CreatedDate' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'FullName', $this->FullName])
            ->andFilterWhere(['like', 'Address', $this->Address])
            ->andFilterWhere(['like', 'CellPhone', $this->CellPhone])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'Remarks', $this->Remarks])
            ->andFilterWhere(['like', 'Type', $this->Type]);

        return $dataProvider;
    }
}
