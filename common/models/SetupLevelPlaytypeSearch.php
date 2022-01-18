<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SetupLevelPlaytype;

/**
 * SetupLevelPlaytypeSearch represents the model behind the search form of `common\models\SetupLevelPlaytype`.
 */
class SetupLevelPlaytypeSearch extends SetupLevelPlaytype
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'minimumPlay', 'maximumPlay', 'gameId', 'createdBy', 'updatedBy'], 'integer'],
            [['codePlayType', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = SetupLevelPlaytype::find();

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
            'minimumPlay' => $this->minimumPlay,
            'maximumPlay' => $this->maximumPlay,
            'jackPotPerUnit' => $this->jackPotPerUnit,
            'gameId' => $this->gameId,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'codePlayType', $this->codePlayType]);

        return $dataProvider;
    }
}
