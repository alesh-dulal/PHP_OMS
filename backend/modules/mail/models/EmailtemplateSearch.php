<?php

namespace backend\modules\mail\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\mail\models\Emailtemplate;

/**
 * EmailtemplateSearch represents the model behind the search form about `backend\modules\mail\models\Emailtemplate`.
 */
class EmailtemplateSearch extends Emailtemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmailTemplateID', 'CreatedBy', 'UpdatedBy'], 'integer'],
            [['Name', 'Details', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Emailtemplate::find();

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
            'EmailTemplateID' => $this->EmailTemplateID,
            'CreatedBy' => $this->CreatedBy,
            'CreatedDate' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Details', $this->Details]);

        return $dataProvider;
    }
}
