<?php
namespace common\models;

use common\libs\Constants;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/26/2018
 * Time: 9:09 PM
 */

class ThaiSharedGameSearch extends ThaiSharedGame
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'status', 'typeSharedGameId'], 'integer'],
            [['createdAt', 'updatedAt', 'startDate', 'endDate'], 'safe'],
            [['round', 'title'], 'string', 'max' => 255],
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
        $query = ThaiSharedGame::find();

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
            'status' => $this->status,
            'gameId' => $this->gameId,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'typeSharedGameId' => $this->typeSharedGameId,
        ]);

        if ($this->startDate) {
            $query->andFilterWhere([
                '=', 'DATE(startDate)', date("Y-m-d", strtotime($this->startDate))
            ]);
        }
        if ($this->endDate) {
            $query->andFilterWhere([
                '=', 'DATE(endDate)', date("Y-m-d", strtotime($this->endDate))
            ]);
        }
        if (!$this->startDate && !$this->endDate){
            $query->andFilterWhere([
                '=', 'DATE(startDate)', date("Y-m-d")
            ]);
        }

        $query->andFilterWhere(['like', 'round', $this->round])
            ->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }
}