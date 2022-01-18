<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LimitAutoLotteryNumberGame;

/**
 * LimitAutoLotteryNumberGameSearch represents the model behind the search form of `common\models\LimitAutoLotteryNumberGame`.
 */
class LimitAutoLotteryNumberGameSearch extends LimitAutoLotteryNumberGame
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thaiSharedGameId', 'playTypeId', 'createdBy', 'updatedBy', 'maximumRate' , 'minimumRate'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['jackPotPerUnit'], 'number'],
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
        $query = LimitAutoLotteryNumberGame::find();

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
            'thaiSharedGameId' => $this->thaiSharedGameId,
            'playTypeId' => $this->playTypeId,
            'jackPotPerUnit' => $this->jackPotPerUnit,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'maximumRate' => $this->maximumRate,
            'minimumRate' => $this->minimumRate,
        ]);

        return $dataProvider;
    }
}
