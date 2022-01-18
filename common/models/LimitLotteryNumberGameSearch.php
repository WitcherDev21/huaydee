<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LimitLotteryNumberGame;

/**
 * LimitLotteryNumberGameSearch represents the model behind the search form of `common\models\LimitLotteryNumberGame`.
 */
class LimitLotteryNumberGameSearch extends LimitLotteryNumberGame
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thaiSharedGameId', 'playTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['number', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = LimitLotteryNumberGame::find()->orderBy(['createdAt' => SORT_DESC, 'number' => SORT_ASC]);

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
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'jackPotPerUnit' => $this->jackPotPerUnit,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }
}
