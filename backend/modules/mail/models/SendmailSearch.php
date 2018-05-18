<?php

namespace backend\modules\mail\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\mail\models\Sendmail;

/**
 * SendmailSearch represents the model behind the search form about `backend\modules\mail\models\Sendmail`.
 */
class SendmailSearch extends Sendmail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MailID', 'CreatedBy', 'UpdatedBy'], 'integer'],
            [['Reciever', 'Subject', 'Message', 'CreatedDate', 'UpdatedDate'], 'safe'],
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
        $query = Sendmail::find();

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
            'MailID' => $this->MailID,
            'CreatedBy' => $this->CreatedBy,
            'CreatedDate' => $this->CreatedDate,
            'UpdatedBy' => $this->UpdatedBy,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'Reciever', $this->Reciever])
            ->andFilterWhere(['like', 'Subject', $this->Subject])
            ->andFilterWhere(['like', 'Message', $this->Message]);

        return $dataProvider;
    }
}
