<?php

namespace backend\modules\payroll\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\payroll\models\Allowencesetting;

/**
 * AllowencesettingSearch represents the model behind the search form of `backend\modules\payroll\models\Allowencesetting`.
 */
class AllowencesettingSearch extends Allowencesetting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AllowenceSettingID', 'IsAllowence', 'Amount', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['Title', 'Formula', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Allowencesetting::find();

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
            'AllowenceSettingID' => $this->AllowenceSettingID,
            'IsAllowence' => $this->IsAllowence,
            'Amount' => $this->Amount,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsDeleted' => $this->IsDeleted,
        ]);

        $query->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'Formula', $this->Formula]);

        return $dataProvider;
    }
}
