<?php

namespace backend\modules\stock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\stock\models\Stockdetail;

/**
 * StockdetailSearch represents the model behind the search form about `backend\models\Stockdetail`.
 */
class StockdetailSearch extends Stockdetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['StockDetailID', 'Qty', 'IsStock', 'UnitID', 'IsActive', 'InsertedBy', 'UpdatedBy', 'ItemID', 'UserID'], 'integer'],
            [['Remarks', 'CreatedDate', 'UpdatedDate', 'ExpiryDate'], 'safe'],
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
        $query = Stockdetail::find()->where('ExpiryDate is not NULL');//find all where stock detail is not null
           $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['ExpiryDate'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'StockDetailID' => $this->StockDetailID,
            'Qty' => $this->Qty,
            'IsStock' => $this->IsStock,
            'UnitID' => $this->UnitID,
            'IsActive' => $this->IsActive,
            'InsertedBy' => $this->InsertedBy,
            'CreatedDate' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'ItemID' => $this->ItemID,
            'UserID' => $this->UserID,
            'ExpiryDate' => $this->ExpiryDate,
        ]);

        $query->andFilterWhere(['like', 'Remarks', $this->Remarks]);

        return $dataProvider;
    }
    
     public function usersearch($params)
    {
        $query = Stockdetail::find();//find all where stock detail is not null
           $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort'=> ['defaultOrder' => ['CreatedDate'=>SORT_DESC]],  
            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'StockDetailID' => $this->StockDetailID,
            'Qty' => $this->Qty,
            'IsStock' => $this->IsStock,
            'UnitID' => $this->UnitID,
            'IsActive' => $this->IsActive,
            'InsertedBy' => $this->InsertedBy,
            'Created Date' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'ItemID' => $this->ItemID,
            'UserID' => $this->UserID,
            'ExpiryDate' => $this->ExpiryDate,
        ]);

        $query->andFilterWhere(['like', 'Remarks', $this->Remarks]);

        return $dataProvider;
    }
}
