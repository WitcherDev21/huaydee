<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lottery_game".
 *
 * @property int $id
 * @property int $gameId
 * @property string $title
 * @property string $startDate
 * @property string $endDate
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $status
 * @property string $createdAt
 * @property string $updateAt
 *
 * @property Games $game
 */
class LotteryGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lottery_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'createdBy', 'updatedBy'], 'required'],
            [['gameId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['startDate', 'endDate', 'createdAt', 'updateAt'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['endDate'], 'validateCheckLimitInMonth', 'on' => 'create'],
            [['gameId'], 'exist', 'skipOnError' => true, 'targetClass' => Games::className(), 'targetAttribute' => ['gameId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = array_merge($scenarios['default'], ['endDate']);
        return $scenarios;
    }


    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['createdBy', 'updatedBy']
                ]
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gameId' => 'Game ID',
            'title' => 'Title',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updateAt' => 'Update At',
            'description' => 'Description'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Games::className(), ['id' => 'gameId']);
    }

    public function getUser()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'createdBy']);
    }

    public function validateCheckLimitInMonth($attribute)
    {
        $month = date("m", strtotime($this->endDate));
        $countLotteryGame = LotteryGame::find()->where(['MONTH(endDate)' => $month, 'gameId' => $this->gameId])->count();
        if ($countLotteryGame >= 2) {
            $this->addError($attribute, 'หวยรัฐเดือนนี้เต็มแล้วครับ');
        }
    }
}
