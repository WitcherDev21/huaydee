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
            'title' => 'หวยหุ้น',
            'gameId' => 'เกมส์',
            'round' => 'รอบ',
            'startDate' => 'วันที่เริ่มต้นเล่น',
            'endDate' => 'วันที่สิ้นสุดการเล่น',
            'createdAt' => 'วันที่สร้าง',
            'updateAt' => 'วันที่แก้ไข',
            'createdBy' => 'เพิ่มโดย',
            'updatedBy' => 'แก้ไขโดย',
            'status' => 'สถานะ',
            'typeSharedGameId' => 'ประเภทหวย',
            'description' => 'คำอธิบาย',
            'disabled' => 'เปืดปิดปุ่ม',
            'result' => 'เลข 6 หลัก',
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
            $this->addError('title', 'หวยเกมนี้ถูกสร้างขึ้นอยู่แล้วในวันที่ '.date("Y-m-d", strtotime($this->endDate)));
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
            'หวยรัฐบาลไทย' => 'หวยรัฐบาลไทย',
            'หวยรัฐบาลไทย แบบมีส่วนลด' => 'หวยรัฐบาลไทย แบบมีส่วนลด',
            'หวยออมสิน' => 'หวยออมสิน',
            'หวย ธกส' => 'หวย ธกส',
            'หวยลาว' => 'หวยลาว',
            'หวยลาว แบบมีส่วนลด' => 'หวยลาว แบบมีส่วนลด',
            'หวยลาวชุด 120' => 'หวยลาวชุด 120',
            'หวยลาวชุด 90' => 'หวยลาวชุด 90',
            'หวยเวียดนามชุด' => 'หวยเวียดนามชุด',
            'หวยมาเลย์' => 'หวยมาเลย์',
            'เวียดนาม/ฮานอย (พิเศษ)' => 'เวียดนาม/ฮานอย (พิเศษ)',
            'เวียดนาม/ฮานอย' => 'เวียดนาม/ฮานอย',
            'หวยหุ้นเกาหลี' => 'หวยหุ้นเกาหลี',
            'หวยหุ้นนิเคอิรอบเช้า' => 'หวยหุ้นนิเคอิรอบเช้า',
            'หวยหุ้นนิเคอิรอบบ่าย' => 'หวยหุ้นนิเคอิรอบบ่าย',
            'หวยหุ้นฮั่งเส็งรอบเช้า' => 'หวยหุ้นฮั่งเส็งรอบเช้า',
            'หวยหุ้นฮั่งเส็งรอบบ่าย' => 'หวยหุ้นฮั่งเส็งรอบบ่าย',
            'หวยหุ้นจีนรอบเช้า' => 'หวยหุ้นจีนรอบเช้า',
            'หวยหุ้นจีนรอบบ่าย' => 'หวยหุ้นจีนรอบบ่าย',
            'หวยหุ้นไต้หวัน' => 'หวยหุ้นไต้หวัน',
            'หวยหุ้นสิงคโปร์' => 'หวยหุ้นสิงคโปร์',
            'หวยหุ้นอียิปต์' => 'หวยหุ้นอียิปต์',
            'หวยหุ้นเยอรมัน' => 'หวยหุ้นเยอรมัน',
            'หวยหุ้นอังกฤษ' => 'หวยหุ้นอังกฤษ',
            'หวยหุ้นรัสเซีย' => 'หวยหุ้นรัสเซีย',
            'หวยหุ้นอินเดีย' => 'หวยหุ้นอินเดีย',
            'หวยหุ้นดาวน์โจน' => 'หวยหุ้นดาวน์โจน',
            'หวยหุ้นไทยเช้า' => 'หวยหุ้นไทยเช้า',
            'หวยหุ้นไทยเที่ยง' => 'หวยหุ้นไทยเที่ยง',
            'หวยหุ้นไทยบ่าย' => 'หวยหุ้นไทยบ่าย',
            'หวยหุ้นไทยเย็น' => 'หวยหุ้นไทยเย็น',
            'หวยฮานอย VIP' => 'หวยฮานอย VIP',
            'หวยฮานอย 4D' => 'หวยฮานอย 4D',
            'หวยลาว จำปาสัก' => 'หวยลาว จำปาสัก',
            'หวยลาวทดแทน' => 'หวยลาวทดแทน',
        ];
    }

    public function getOptions($title)
    {
        if ($title === 'หวยรัฐบาลไทย' || $title === 'หวยรัฐบาลไทย แบบมีส่วนลด') {
            $icon = 'fas fa-crown';
            $classHead = 'lotto-head lotto-government';
        } elseif ($title === 'หวยลาว' || $title === 'หวยลาว แบบมีส่วนลด') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-la';
        } elseif ($title === 'หวยออมสิน') {
            $icon = 'flag-icon flag-icon-gsb';
            $classHead = 'lotto-head lotto-gsb';
        } elseif ($title === 'หวย ธกส' || $title === 'หวยธกส') {
            $icon = 'flag-icon flag-icon-baac';
            $classHead = 'lotto-head lotto-baac';
        } elseif ($title === 'หวยลาวชุด 120' || $title === 'หวยลาวชุด 90') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-la';
        }  elseif ($title === 'หวยมาเลย์') {
            $icon = 'flag-icon flag-icon-my';
            $classHead = 'lotto-head lotto-my';
        } elseif ($title === 'เวียดนาม/ฮานอย แบบมีส่วนลด' || $title === 'หวยเวียดนามชุด' || $title === 'เวียดนาม/ฮานอย') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn';
        } elseif ($title === 'เวียดนาม/ฮานอย (พิเศษ)') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn-special';
        } elseif($title === 'หวยฮานอย VIP') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn-vip';
        } elseif ($title === 'หวยหุ้นรัสเซีย') {
            $icon = 'flag-icon flag-icon-ru';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นเกาหลี') {
            $icon = 'flag-icon flag-icon-kr';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นนิเคอิรอบเช้า' || $title === 'หวยหุ้นนิเคอิรอบบ่าย') {
            $icon = 'flag-icon flag-icon-jp';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นฮั่งเส็งรอบเช้า' || $title === 'หวยหุ้นฮั่งเส็งรอบบ่าย') {
            $icon = 'flag-icon flag-icon-hk';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นจีนรอบเช้า' || $title === 'หวยหุ้นจีนรอบบ่าย') {
            $icon = 'flag-icon flag-icon-cn';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นไต้หวัน') {
            $icon = 'flag-icon flag-icon-tw';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นสิงคโปร์') {
            $icon = 'flag-icon flag-icon-sg';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นอียิปต์') {
            $icon = 'flag-icon flag-icon-eg';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นเยอรมัน') {
            $icon = 'flag-icon flag-icon-de';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นอังกฤษ') {
            $icon = 'flag-icon flag-icon-gb';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นอินเดีย') {
            $icon = 'flag-icon flag-icon-in';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นดาวน์โจน') {
            $icon = 'flag-icon flag-icon-us';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยหุ้นไทยเช้า' || $title === 'หวยหุ้นไทยเที่ยง' || $title === 'หวยหุ้นไทยบ่าย' || $title === 'หวยหุ้นไทยเย็น') {
            $icon = 'flag-icon flag-icon-th';
            $classHead = 'lotto-head lotto-foreignstock';
        } elseif ($title === 'หวยลาว จำปาสัก') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-lao-champasak';
        } elseif ($title === 'หวยฮานอย 4D') {
            $icon = 'flag-icon flag-icon-vn';
            $classHead = 'lotto-head lotto-vn-4d';
        } elseif ($title === 'หวยลาวทดแทน') {
            $icon = 'flag-icon flag-icon-la';
            $classHead = 'lotto-head lotto-lao-substitute';
        }
        $options['icon'] = $icon;
        $options['classHead'] = $classHead;
        return $options;
    }
}