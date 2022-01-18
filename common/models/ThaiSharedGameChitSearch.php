<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LotteryGameChitSearch represents the model behind the search form of `common\models\LotteryGameChit`.
 */
class ThaiSharedGameChitSearch extends ThaiSharedGameChit
{
    public $title;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thaiSharedGameId', 'userId', 'createdBy', 'status', 'updatedBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['totalAmount'], 'number'],
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
        $query = ThaiSharedGameChit::find()->joinWith('thaiSharedGame');

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
            'userId' => $this->userId,
            'createdBy' => $this->createdBy,
            'totalAmount' => $this->totalAmount,
            'status' => $this->status,
            'updatedAt' => $this->updatedAt,
            'updatedBy' => $this->updatedBy,
            ThaiSharedGame::tableName().'.title' => $this->title,
        ]);

        if ($this->createdAt) {
            $query->andFilterWhere([
                '=', 'DATE('. ThaiSharedGameChit::tableName(). '.createdAt)', date("Y-m-d", strtotime($this->createdAt))
            ]);
        }
        return $dataProvider;
    }
}
