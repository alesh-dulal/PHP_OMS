<?php

namespace backend\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\models\Listitems;

/**
 * ListitemsSearch represents the model behind the search form about `backend\modules\user\models\Listitems`.
 */
class ListitemsSearch extends Listitems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ListItemID', 'ParentID', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Type', 'Title', 'Value', 'Options', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Listitems::find();

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
            'ListItemID' => $this->ListItemID,
            'ParentID' => $this->Parent,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsDeleted' => $this->IsDeleted,
        ]);

        $query->andFilterWhere(['like', 'Type', $this->Type])
            ->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Value', $this->Value])
            ->andFilterWhere(['like', 'Options', $this->Options]);

        return $dataProvider;
    }
}
