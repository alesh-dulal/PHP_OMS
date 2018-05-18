<?php

namespace backend\modules\stock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\stock\models\Stock;

/**
 * StockSearch represents the model behind the search form about `backend\models\Stock`.
 */
class StockSearch extends Stock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['StockID', 'Qty', 'InsertedBy', 'UpdatedBy', 'ItemID'], 'integer'],
            [['IsActive', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Stock::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        $dataProvider->setSort([
//            'attributes'=>[
//                'StockID'=>[
//                    'desc'=>['StockID'=>SORT_DESC]
//                ]
//            ]
//        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'StockID' => $this->StockID,
            'Qty' => $this->Qty,
            'InsertedBy' => $this->InsertedBy,
            'CreatedDate' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'ItemID' => $this->ItemID,
            
        ]);

        $query->andFilterWhere(['like', 'IsActive', $this->IsActive]);

        return $dataProvider;
    }
}
