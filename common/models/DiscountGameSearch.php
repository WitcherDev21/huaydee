<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DiscountGame;

/**
 * DiscountGameSearch represents the model behind the search form of `common\models\DiscountGame`.
 */
class DiscountGameSearch extends DiscountGame
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'playTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['discount'], 'number'],
            [['title'], 'string'],
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
        $query = DiscountGame::find()->orderBy('createdAt DESC');

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
            'discount' => $this->discount,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
