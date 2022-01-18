<?php


namespace common\models;

use common\libs\Constants;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * LotteryGameChitDetailSearch represents the model behind the search form of `common\models\LotteryGameChitDetail`.
 */
class ThaiSharedGameChitDetailSearch extends ThaiSharedGameChitDetail
{
    public $title;
    public $endDate;
    public $thaiSharedGameId;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'playTypeId', 'amount', 'flag_result', 'userId', 'createdBy', 'thaiSharedGameChitId'], 'integer'],
            [['number', 'createdAt', 'title', 'endDate'], 'safe'],
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
    public function search($params, $gameId = null)
    {
        $query = ThaiSharedGameChitDetail::find();
        if ($gameId === Constants::LOTTERYLAOGAME || $gameId === Constants::LOTTERYLAODISCOUNTGAME) {
            $query->joinWith('playType');
            $query->groupBy('numberSetLottery');
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
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
            'playTypeId' => $this->playTypeId,
            'amount' => $this->amount,
            'flag_result' => $this->flag_result,
            'win_credit' => $this->win_credit,
            'userId' => $this->userId,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'thaiSharedGameChitId' => $this->thaiSharedGameChitId,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }


    public function searchReportGame($params)
    {
        $query = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where(ThaiSharedGameChit::tableName().'.status != '.Constants::status_cancel)
            ->orderBy(ThaiSharedGameChitDetail::tableName().'.userId');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!$this->createdAt) {
            $this->createdAt = date('Y-m-d');
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'DATE('.ThaiSharedGameChitDetail::tableName().'.createdAt)' => $this->createdAt,
        ]);

        return $dataProvider;
    }

    public function searchResultNumber($params)
    {
        $query = ThaiSharedGameChitDetail::find()->select('number, playTypeId, thaiSharedGameChitId, '.ThaiSharedGameChitDetail::tableName().'.createdAt, COUNT(thaiSharedGameChitId) AS chit_amount, SUM(amount) AS amount')
            ->innerJoin(ThaiSharedGameChit::tableName(),
                ''.ThaiSharedGameChitDetail::tableName().'.thaiSharedGameChitId = '.ThaiSharedGameChit::tableName().'.id'
            )->innerJoin(ThaiSharedGame::tableName(),
                ''.ThaiSharedGameChit::tableName().'.thaiSharedGameId = '.ThaiSharedGame::tableName().'.id'
            )->groupBy('number')->orderBy('amount DESC');
        //,
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!$this->createdAt) {
            $this->createdAt = date('Y-m-d');
        }
        if (!$this->playTypeId) {
           $this->playTypeId = NULL;
        }
        if (!$this->title) {
            $this->title = NULL;
        }
        // grid filtering conditions
        if (!$this->endDate) {
            $query->andFilterWhere(['DATE('.ThaiSharedGameChitDetail::tableName().'.createdAt)' => $this->createdAt]);
        }else{
            $query->andFilterWhere(['between', 'DATE('.ThaiSharedGameChitDetail::tableName().'.createdAt)', $this->createdAt, $this->endDate]);
        }
        $query->andFilterWhere([
            ThaiSharedGameChitDetail::tableName().'.playTypeId' => $this->playTypeId,
            ThaiSharedGame::tableName().'.title' => $this->title
        ]);
        return $dataProvider;
    }

    public function previewAnswerSearch($params)
    {
        $query = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $numberArray = ['null'];
        if ($this->number) {
            foreach ($this->number as $numbers) {
                if (is_array($numbers)) {
                    foreach ($numbers as $number) {
                        $numberArray[] = $number;
                    }
                } else {
                    $numberArray[] = $numbers;
                }
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'playTypeId' => $this->playTypeId,
            'amount' => $this->amount,
            'flag_result' => $this->flag_result,
            'win_credit' => $this->win_credit,
            'userId' => $this->userId,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'thaiSharedGameChitId' => $this->thaiSharedGameChitId,
            'thaiSharedGameId' => $this->thaiSharedGameId,
        ]);
        $query->andFilterWhere(['in', 'number', $numberArray]);
        return $dataProvider;
    }
        public function searchDetail($params)
    {
        
        $query = ThaiSharedGameChitDetail::find();
        //,
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        $this->load($params);
        //var_dump($params->endDate);exit();
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!$params['createdAt']) {
            $params['createdAt'] = date('Y-m-d');
        }
        if (!$params['playTypeId']) {
           $params['playTypeId'] = NULL;
        }
        if (!$params['title']) {
            $params['title'] = NULL;
        }
        // grid filtering conditions
        if (!$params['endDate']) {
            $query->andFilterWhere(['DATE('.ThaiSharedGameChitDetail::tableName().'.createdAt)' => $params['createdAt']]);
        }else{
            $query->andFilterWhere(['between', 'DATE('.ThaiSharedGameChitDetail::tableName().'.createdAt)', $params['createdAt'], $params['endDate']]);
        }
     
        return $dataProvider;
    }

}
