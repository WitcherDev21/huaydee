<?php
namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/26/2018
 * Time: 9:09 PM
 *
 *
 * @property int $id
 * @property string $round
 * @property string $title
 * @property string $description
 * @property int $gameId
 * @property int $status
 * @property int $startDate
 * @property int $endDate
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $typeSharedGameId
 * @property int $disabled
 * @property string $result
 * @property int    $limitAuto
 */

class ThaiSharedGame extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thai_shared_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'startDate', 'endDate', 'status', 'title', 'typeSharedGameId', 'disabled'], 'required'],
            [['gameId', 'status', 'typeSharedGameId', 'disabled', 'limitAuto'], 'integer'],
            [['createdAt', 'updateAt', 'updatedBy'], 'safe'],
            [['round', 'title', 'result'], 'string', 'max' => 255],
            ['title', 'validateUniqueTitleByDate', 'on' => 'create'],
            [['description'], 'string'],
            [['gameId'], 'exist', 'skipOnError' => true, 'targetClass' => Games::className(), 'targetAttribute' => ['gameId' => 'id']],
            [['typeSharedGameId'], 'exist', 'skipOnError' => true, 'targetClass' => TypeGameShared::className(), 'targetAttribute' => ['typeSharedGameId' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = array_merge($scenarios['default'], ['title']);
        return $scenarios;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updateAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '?????????????????????',
            'gameId' => '???????????????',
            'round' => '?????????',
            'startDate' => '??????????????????????????????????????????????????????',
            'endDate' => '????????????????????????????????????????????????????????????',
            'createdAt' => '?????????????????????????????????',
            'updateAt' => '?????????????????????????????????',
            'createdBy' => '????????????????????????',
            'updatedBy' => '????????????????????????',
            'status' => '???????????????',
            'typeSharedGameId' => '???????????????????????????',
            'description' => '????????????????????????',
            'disabled' => '?????????????????????????????????',
            'result' => '????????? 6 ????????????',
            'limitAuto' => 'Is Limit Auto',
        ];
    }

    public function getGame()
    {
        return $this->hasOne(Games::className(), ['id' => 'gameId']);
    }

    public function getUser()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'createdBy']);
    }

    public function validateUniqueTitleByDate()
    {
        $thaiSharedGameByUnique = ThaiSharedGame::find()->where(['=', 'DATE(startDate)', date("Y-m-d", strtotime($this->endDate))])->andWhere(['title' => $this->title])->count();
        if ($thaiSharedGameByUnique) {
            $this->addError('title', '??????????????????????????????????????????????????????????????????????????????????????????????????????????????? '.date("Y-m-d", strtotime($this->endDate)));
        }
    }

    public function getTypeGameShared()
    {
        return $this->hasOne(TypeGameShared::className(), ['id' => 'typeSharedGameId']);
    }

    public function getThaiSharedGameChit()
    {
        return $this->hasMany(ThaiSharedGameChit::className(), ['thaiSharedGameId' => 'id']);
    }

    public function getThaiSharedAnswerGame()
    {
        return $this->hasMany(ThaiSharedAnswerGame::className(), ['thaiSharedGameId' => 'id']);
    }

    public function getTitles()
    {
        return [
            '????????????????????????????????????' => '????????????????????????????????????',
            '???????????????????????????????????? ?????????????????????????????????' => '???????????????????????????????????? ?????????????????????????????????',
            '???????????????????????????' => '???????????????????????????',
            '????????? ?????????' => '????????? ?????????',
            '??????????????????' => '??????????????????',
            '?????????????????? ?????????????????????????????????' => '?????????????????? ?????????????????????????????????',
            '??????????????????????????? 120' => '??????????????????????????? 120',
            '??????????????????????????? 90' => '??????????????????????????? 90',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '???????????????????????????' => '???????????????????????????',
            '????????????????????????/??????????????? (???????????????)' => '????????????????????????/??????????????? (???????????????)',
            '????????????????????????/???????????????' => '????????????????????????/???????????????',
            '???????????????????????????????????????' => '???????????????????????????????????????',
            '????????????????????????????????????????????????????????????' => '????????????????????????????????????????????????????????????',
            '????????????????????????????????????????????????????????????' => '????????????????????????????????????????????????????????????',
            '??????????????????????????????????????????????????????????????????' => '??????????????????????????????????????????????????????????????????',
            '??????????????????????????????????????????????????????????????????' => '??????????????????????????????????????????????????????????????????',
            '???????????????????????????????????????????????????' => '???????????????????????????????????????????????????',
            '???????????????????????????????????????????????????' => '???????????????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '?????????????????????????????????????????????' => '?????????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '???????????????????????????????????????' => '???????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '?????????????????????????????????????????????' => '?????????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '????????????????????????????????????????????????' => '????????????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '??????????????????????????????????????????' => '??????????????????????????????????????????',
            '???????????????????????? VIP' => '???????????????????????? VIP',
            '???????????????????????? 4D' => '???????????????????????? 4D',
            '?????????????????? ?????????????????????' => '?????????????????? ?????????????????????',
            '?????????????????????????????????' => '?????????????????????????????????',
        ];
    }

    public function getOptions($title)
    {
        if ($title === '????????????????????????????????????' || $title === '???????????????????????????????????? ?????????????????????????????????') {
            $icon = 'fas fa-crown';
            $classHead = 'lotto-head lotto-government';
        } elseif ($title === '??????????????????' || $title === '?????????????????? ?????????????????????????????????') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-la';
        } elseif ($title === '???????????????????????????') {
            $icon = 'flag-icon flag-icon-gsb';
            $classHead = 'lotto-head lotto-gsb';
        } elseif ($title === '????????? ?????????' || $title === '??????????????????') {
            $icon = 'flag-icon flag-icon-baac';
            $classHead = 'lotto-head lotto-baac';
        } elseif ($title === '??????????????????????????? 120' || $title === '??????????????????????????? 90') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-la';
        }  elseif ($title === '???????????????????????????') {
            $icon = 'flag-icon flag-icon-my';
            $classHead = 'lotto-head lotto-my';
        } elseif ($title === '????????????????????????/??????????????? ?????????????????????????????????' || $title === '??????????????????????????????????????????' || $title === '????????????????????????/???????????????') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn';
        } elseif ($title === '????????????????????????/??????????????? (???????????????)') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn-special';
        } elseif($title === '???????????????????????? VIP') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn-vip';
        } elseif ($title === '??????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-ru';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '???????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-kr';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '????????????????????????????????????????????????????????????' || $title === '????????????????????????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-jp';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '??????????????????????????????????????????????????????????????????' || $title === '??????????????????????????????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-hk';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '???????????????????????????????????????????????????' || $title === '???????????????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-cn';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '??????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-tw';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '?????????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-sg';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '??????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-eg';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '??????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-de';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '???????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-gb';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '??????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-in';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '?????????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-us';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '??????????????????????????????????????????' || $title === '????????????????????????????????????????????????' || $title === '??????????????????????????????????????????' || $title === '??????????????????????????????????????????') {
            $icon = 'flag-icon flag-icon-th';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === '?????????????????? ?????????????????????') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-lao-champasak';
        } elseif ($title === '???????????????????????? 4D') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn-4d';
        } elseif ($title === '?????????????????????????????????') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-lao-substitute';
        }
        $options['icon'] = $icon;
        $options['classHead'] = $classHead;
        return $options;
    }
}