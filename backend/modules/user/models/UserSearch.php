<?php

namespace backend\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\models\User;

/**
 * UserSearch represents the model behind the search form about `backend\modules\user\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserId', 'IsPasswordReset', 'CreatedBy', 'UpdatedBy', 'IsActive', 'IsDeleted'], 'integer'],
            [['UserName', 'auth_key', 'Password', 'PasswordKey', 'Email', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = User::find();

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
            'UserId' => $this->UserId,
            'IsPasswordReset' => $this->IsPasswordReset,
            'CreatedDate' => $this->CreatedDate,
            'CreatedBy' => $this->CreatedBy,
            'UpdatedDate' => $this->UpdatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'IsActive' => $this->IsActive,
            'IsDeleted' => $this->IsDeleted,
        ]);

        $query->andFilterWhere(['like', 'UserName', $this->UserName])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'Password', $this->Password])
            ->andFilterWhere(['like', 'PasswordKey', $this->PasswordKey])
            ->andFilterWhere(['like', 'Email', $this->Email]);

        return $dataProvider;
    }
}
