<?php

namespace backend\modules\holiday\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\holiday\models\Holiday;

/**
 * HolidaySearch represents the model behind the search form about `backend\modules\holiday\models\Holiday`.
 */
class HolidaySearch extends Holiday
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['HolidayID', 'Year', 'IsActive', 'InsertedBy', 'UpdatedBy'], 'integer'],
            [['Name', 'Description', 'Day', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Holiday::find();

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
            'HolidayID' => $this->HolidayID,
            'Day' => $this->Day,
            'Year' => $this->Year,
            'IsActive' => $this->IsActive,
            'InsertedBy' => $this->InsertedBy,
            'InsertedDate' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Description', $this->Description]);

        return $dataProvider;
    }
}
