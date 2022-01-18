<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlackredChitSearch represents the model behind the search form of `common\models\BlackredChit`.
 */
class BlackredChitSearch extends BlackredChit
{
    public $date_at;
    public $round;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'blackred_id', 'total_amount', 'create_by', 'update_by', 'status', 'play_type_code', 'flag_result', 'round'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['win_credit'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = BlackredChit::find()->joinWith('blackred');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'blackred_id' => $this->blackred_id,
            'total_amount' => $this->total_amount,
            'create_at' => $this->create_at,
            BlackredChit::tableName(). '.create_by' => $this->create_by,
            BlackRed::tableName(). '.round' => $this->round,
            'update_at' => $this->update_at,
            'update_by' => $this->update_by,
            'status' => $this->status,
            'play_type_code' => $this->play_type_code,
            'flag_result' => $this->flag_result,
            'win_credit' => $this->win_credit,
        ]);
        if ($this->date_at) {
            $query->andFilterWhere([
                '=', BlackRed::tableName() . '.date_at', date("Y-m-d", strtotime($this->date_at))
            ]);
        }

        return $dataProvider;
    }
}