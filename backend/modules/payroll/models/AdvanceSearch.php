<?php

namespace backend\modules\payroll\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\payroll\models\Advance;

/**
 * AdvanceSearch represents the model behind the search form of `backend\modules\payroll\models\Advance`.
 */
class AdvanceSearch extends Advance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AdvanceID', 'EmployeeID', 'Amount', 'Rule', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['CreatedDate', 'UpdatedDate', 'Month'], 'safe'],
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
        $query = Advance::find();

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
            'AdvanceID' => $this->AdvanceID,
            'EmployeeID' => $this->EmployeeID,
            'Amount' => $this->Amount,
            'Rule' => $this->Rule,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsDeleted' => $this->IsDeleted,
            'Month' => $this->Month,
        ]);

        return $dataProvider;
    }
}
