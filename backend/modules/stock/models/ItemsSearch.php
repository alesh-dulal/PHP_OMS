<?php

namespace backend\modules\stock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\stock\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `backend\models\Items`.
 */
class ItemsSearch extends Items
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ItemID', 'CategoryID', 'IsActive', 'InserstedBy', 'UnitID'], 'integer'],
            [['Name', 'CreatedDate', 'UpdatedBy', 'UpdateDate'], 'safe'],
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
        $query = Items::find();

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
            'ItemID' => $this->ItemID,
            'CategoryID' => $this->CategoryID,
            'IsActive' => $this->IsActive,
            'InserstedBy' => $this->InserstedBy,
            'CreatedDate' => $this->CreatedDate,
            'UpdateDate' => $this->UpdateDate,
            'UnitID' => $this->UnitID,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'UpdatedBy', $this->UpdatedBy]);

        return $dataProvider;
    }
}
