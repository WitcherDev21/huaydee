<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LimitLotteryByGamePlayTypeSet;

/**
 * LimitLotteryByGamePlayTypeSetSearch represents the model behind the search form of `common\models\LimitLotteryByGamePlayTypeSet`.
 */
class LimitLotteryByGamePlayTypeSetSearch extends LimitLotteryByGamePlayTypeSet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'playTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['title', 'number'], 'safe'],
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
        $query = LimitLotteryByGamePlayTypeSet::find();

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
            'id' => $this->id,
            'playTypeId' => $this->playTypeId,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }
}
