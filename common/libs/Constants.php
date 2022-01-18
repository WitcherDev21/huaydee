<?php

namespace common\libs;

use common\models\PlayType;
use common\models\User;
use Yii;

class Constants
{
    // game id
    const YEEKEE = 1;
    const BLACKRED = 2;
    const THAISHARED = 26;
    const LOTTERYGAME = 27;
    const LOTTERYGAMEDISCOUNT = 28;
    const LOTTERYLAOGAME = 33;
    const LOTTERYLAODISCOUNTGAME = 34;
    const TYPESHAREDGAMETHAIID = 1;
    const TYPESHAREDGAMEABROADID = 2;
    const TYPESHAREDGAMEDOUBLEID = 3;
    const TYPELOTTERYID = 4;
    const TYPELOTTERYDISSCOUNTID = 5;
    const LOTTERY_VIETNAM_SET = 37;
    const VIETNAMVIP = 38;
    const BACC_THAISHARD_GAME = 39;
    const GSB_THAISHARD_GAME = 40;
    const LAOS_CHAMPASAK_LOTTERY_GAME = 41;
    const VIETNAM4D_GAME = 42;
    const LOTTERYRESERVEGAME = 43;
    const SETUP_LEVEL_PLAYTYPE_GAMEID = [
        0 => [self::LOTTERYGAME, self::LOTTERYGAMEDISCOUNT, self::GSB_THAISHARD_GAME, self::BACC_THAISHARD_GAME],
        1 => [self::THAISHARED]
    ];
    const SETUP_LEVEL_PLAYTYPE_NAME = [
        0 => ['3ตัวบน', '3ตัวโต๊ด', '2ตัวบน', '2ตัวล่าง', 'วิ่งบน', 'วิ่งล่าง', '3ตัวหน้า', '3ตัวล่าง'],
        1 => ['3ตัวบน', '3ตัวโต๊ด', '2ตัวบน', '2ตัวล่าง', 'วิ่งบน', 'วิ่งล่าง']
    ];
    const SETUP_LEVEL_PLAYTYPE_CODE = [
        0 => ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'],
        1 => ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under']
    ];
    const ROULETTE = 4;
    const BACCARAT = 3;
    const BLACK = 1;
    const RED = 2;
    const post_frequency_per_sec = 5; // ความถี่ในการ ยิง game yeekee
    const minimum_post = 20; // การยิงเลขขั้นต่ำ ในแต่ละการออก yeekee
    const minimum_play_for_pay_credit = 100; //ขั้นต่ำในการแทง yeekee เพื่อที่จะได้เงินโบนัส ในลำดับที่ 1, 16
    const bonus_yeekee_post_order_code_1 = 'post_order_1';     //ถ้ายิงได้ ลำดับ ที่ 1 จะได้เครดิต พิเศษ 100
    const bonus_yeekee_post_order_code_16 = 'post_order_16';    //ถ้ายิงได้ ลำดับ ที่ 16 จะได้เครดิต พิเศษ 200
    const minimum_set_all = 1; //set เล่นขั้นต่ำทั้งหมด
    const maximum_set_all = 2500; //set เล่นสูงสุดต่อเลข
    const maximum_play_per_chit = 500;
    const minimum_create_withdraw = 1; //ชั้นต่ำการถอนเงิน
    const minimum_create_topup = 1; //ขั้นต่ำการฝากเงิน
    const maximum_post_credit_transection = 5; //จำนวนแจ้งถอนสูงสุด
    const config_percent_withdraw_from_bet_amount = 80; //เปอร์เซ็น ยอดแทงจากยอดฝากที่จะทำให้ถอนได้

    const PLAY_TYPE_THAI_SHARED_THREE_TOP = 9;
    const PLAY_TYPE_THAI_SHARED_THREE_TOP_VIETNAM_VIP = 77;
    const PLAY_TYPE_LOTTERY_GAME = 19;
    const PLAY_TYPE_LOTTERY_DISCOUNT_GAME = 27;
    const PLAY_TYPE_THREE_TOP_LAOS_CHAMPASAK_LOTTERY_GAME = 95;
    const PLAY_TYPE_THREE_TOP_GSB_GAME = 89;
    const PLAY_TYPE_THREE_TOP_BACC_GAME = 83;
    const PLAY_TYPE_THREE_TOP_VITE_NAME_GAME = 101;
    const PLAY_TYPE_THREE_TOP_LAOS_SUBSTITUTE = 107;
    const user_system_id = 53;

    const status_active = 1;
    const status_inactive = 0;
    const status_withhold = 2;
    const status_processed = 3;
    const status_processing = 4;
    const status_cancel = 5;
    const status_waitting = 6;
    const status_approve = 7;
    const status_playing = 8;
    const status_finish_show_result = 9;
    const status_processing_2 = 10;

    const action_credit_top_up = 1;
    const action_credit_withdraw = 2;
    const action_credit_bet_play = 3;
    const action_credit_cancel = 4;
    const action_credit_top_up_admin = 5;
    const action_credit_withdraw_admin = 6;
    const action_credit_master_top_up = 7;
    const action_credit_master_withdraw = 8;
    const action_credit_top_up_admin_special = 9;
    const action_credit_withdraw_admin_special = 10;

    const reason_credit_top_up = 1;
    const reason_credit_withdraw = 2;
    const reason_credit_bet_play = 3;
    const reason_credit_cancel = 4;
    const reason_credit_bet_win = 5;
    const reason_credit_return_chit = 6;
    const reason_credit_yeekee_post_1 = 7;
    const reason_credit_yeekee_post_16 = 8;
    const reason_credit_commission_in = 9;
    const reason_credit_top_up_promotion = 10;
    const reason_credit_withdraw_promotion = 11;
    const reason_credit_withdraw_direct = 12;

    const auth_roles_super_admin = 1;
    const auth_roles_admin = 2;
    const auth_roles_agent = 3;
    const auth_roles_member = 4;

    const user_status_un_active = 0;
    const user_status_active = 1;
    const user_status_withhold = 2;

    const private_key = 'WTF!';
    const color_credit_in = '#5eba7d';
    const color_credit_out = '#ff0000';

    const minimum_commission_withdraw = 500; //ถอน commission ขั้นต่ำ
    const maximum_commission_withdraw = 10000; //ถอน commission สูงสุด

    const action_commission_top_up = 1;
    const action_commission_withdraw = 2;
    const action_commission_withdraw_direct = 3;

    const reason_commission_top_up = 1;
    const reason_commission_withdraw = 2;
    const reason_commission_withdraw_direct = 3;

    const setting_commission_credit_invite = 1;
    const setting_commission_credit_agent = 2;
    const setting_commission_credit_credit = 3;
    const setting_commission_game_blackred_credit_invite = 4;
    const app_key = '1f994d31b652d59118af';
    const app_secret = '9aea3bfd3b1bc23fb76c';
    const app_id = '817938';
    const app_cluster = 'ap1';
    const app_channel = 'my-channel';
    const app_event = 'my-event';
    const LIMIT_THAI_SHARED_ANSWER_GAME = 500;
    public static $setting_commission_credit = [
        self::setting_commission_credit_invite => 'คอมมิชชั่นแนะนำ',
        self::setting_commission_credit_agent => 'คอมมิชชั่นเอเยนต์',
        self::setting_commission_credit_credit => 'เติมเงินเครดิต-ประจำวัน'
    ];

    const blackred_black = 1;
    const blackred_red = 2;
    public static $blackred_result = [
        self::blackred_black => 'ดำ',
        self::blackred_red => 'แดง'
    ];
    const setting_benefit_is_income = 1;
    const setting_benefit_is_normal = 2;
    public static $setting_benefit = [
        self::setting_benefit_is_income => 'ออกผลรางวัลที่แทงน้อย',
        self::setting_benefit_is_normal => 'ไม่กำหนด'
    ];
    public static $status = [
        self::status_active => 'active',
        self::status_inactive => 'ปิดรับแทง',
        self::status_withhold => 'ระงับ',
        self::status_processed => 'ดำเนินการแล้ว',
        self::status_processing => 'กำลังดำเนินการ',
        self::status_processing_2 => 'กำลังดำเนินการ',
        self::status_waitting => 'รอดำเนินการ',
        self::status_approve => 'อนุมัติ',
        self::status_cancel => 'ยกเลิก',
        self::status_playing => 'รับแทง',
        self::status_finish_show_result => 'ออกผล'
    ];
    public static $statusIcon = [
        self::status_active => 'success',
        self::status_inactive => 'danger',
        self::status_withhold => 'danger',
        self::status_processed => 'success',
        self::status_processing => 'warning',
        self::status_processing_2 => 'warning',
        self::status_waitting => 'warning',
        self::status_approve => 'success',
        self::status_cancel => 'danger',
        self::status_playing => 'info',
        self::status_finish_show_result => 'success'
    ];
    public static $statusAction = [
        self::status_waitting => 'รอดำเนินการ',
        self::status_approve => 'อนุมัติ',
        self::status_cancel => 'ยกเลิก'
    ];
    public static $action_credit = [
        self::action_credit_top_up => 'เติมเครดิต',
        self::action_credit_withdraw => 'ถอนเครดิต',
        self::action_credit_bet_play => 'แทงพนัน',
        self::action_credit_cancel => 'ยกเลิก',
        self::action_credit_top_up_admin => 'เติมเครดิตโปรโมชั่น',
        self::action_credit_withdraw_admin => 'ถอนตรง',
        self::action_credit_master_top_up => 'เติมเครดิตมาสเตอร์',
        self::action_credit_master_withdraw => 'ถอนเครดิตมาสเตอร์',
        self::action_credit_top_up_admin_special => 'เติมตรงพิเศษ',
        self::action_credit_withdraw_admin_special => 'ถอนตรงพิเศษ',
    ];
    public static $reason_credit = [
        self::reason_credit_top_up => 'เติมเครดิต',            //เพิ่มเงิน
        self::reason_credit_withdraw => 'ถอนเครดิต',         //ลดเงิน
        self::reason_credit_bet_play => 'แทงพนัน',          //ลดเงิน
        self::reason_credit_cancel => 'ยกเลิก',             //เพิ่มเงิน
        self::reason_credit_bet_win => 'ชนะพนัน',           //เพิ่มเงิน
        self::reason_credit_return_chit => 'คืนโพย',        //เพิ่มเงิน
        self::reason_credit_yeekee_post_1 => 'ยิงเลขลำดับ 1',   //เพิ่มเงิน
        self::reason_credit_yeekee_post_16 => 'ยิงเลขลำดับ 16', //เพิ่มเงิน
        self::reason_credit_commission_in => 'ค่าคอมมิชชั่น',      //เพิ่มเงิน
        self::reason_credit_top_up_promotion => 'เติมเครดิตโปรโมชั่น',
        self::reason_credit_withdraw_promotion => 'ถอนเครดิตโปรโมชั่น',
        self::reason_credit_withdraw_direct => 'ถอนตรง',
    ];
    public static $reason_credit_color = [
        self::reason_credit_top_up => '#26C281',
        self::reason_credit_withdraw => '#ff1ac6',
        self::reason_credit_bet_play => '#3598DC',
        self::reason_credit_cancel => '#e7505a',
        self::reason_credit_bet_win => '#ffa800',
        self::reason_credit_return_chit => '#ababab',
        self::reason_credit_yeekee_post_1 => '#ff81ef',
        self::reason_credit_yeekee_post_16 => '#ff81ef',
        self::reason_credit_commission_in => '#ababab',
        self::reason_credit_top_up_promotion => '#3386FF',
        self::reason_credit_withdraw_promotion => '#7DFF33',
        self::reason_commission_withdraw_direct => '#BB05A7',
        self::reason_credit_withdraw_direct => '#F90606',
    ];
    public static $reason_credit_class = [
        self::reason_credit_top_up => 'badge badge-success font-weight-light w-auto',
        self::reason_credit_withdraw => 'badge badge-danger font-weight-light w-auto',
        self::reason_credit_bet_play => 'badge badge-warning font-weight-light w-auto',
        self::reason_credit_cancel => 'badge badge-info font-weight-light w-auto',
        self::reason_credit_bet_win => 'badge badge-success font-weight-light w-auto',
        self::reason_credit_return_chit => 'badge badge-info font-weight-light w-auto',
        self::reason_credit_yeekee_post_1 => 'badge badge-success font-weight-light w-auto',
        self::reason_credit_yeekee_post_16 => 'badge badge-success font-weight-light w-auto',
        self::reason_credit_commission_in => 'badge badge-success font-weight-light w-auto',
        self::reason_credit_top_up_promotion => 'badge badge-success font-weight-light w-auto',
        self::reason_credit_withdraw_promotion => 'badge badge-danger font-weight-light w-auto',
        self::reason_credit_withdraw_direct => 'badge badge-danger font-weight-light w-auto',
    ];

    public static $action_commission = [
        self::action_commission_top_up => 'ได้ค่าคอมมิชชั่น',
        self::action_commission_withdraw => 'ถอนค่าคอมมิชชั่น',
        self::action_commission_withdraw_direct => 'ถอนตรงค่าคอมมิชชั่น',
    ];
    public static $reason_commission = [
        self::reason_commission_top_up => 'ได้ค่าคอมมิชชั่น',            //เพิ่มเงิน
        self::reason_commission_withdraw => 'ถอนค่าคอมมิชชั่น',         //ลดเงิน
    ];

    public static $auth_roles = [
        self::auth_roles_super_admin => 'Super Admin',
        self::auth_roles_admin => 'Admin',
        self::auth_roles_agent => 'Agent',
        self::auth_roles_member => 'Member',
    ];

    public static $user_status = [
        self::user_status_un_active => 'ยังไม่ยืนยัน',
        self::user_status_active => 'ปกติ',
        self::user_status_withhold => 'ระงับ',
    ];
    public static $menu_frontend = [

    ];

    public static function menuFrontend()
    {
        $menus = [
            [
                'icon' => 'fa fa-money',
                'title' => 'รายการพนัน',
                'uri' => 'lotto/index',
                'group' => [
                    'lotto/index'
                ],
                'sub' => [],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,

            ],
            [
                'icon' => 'fa fa-building-o',
                'title' => 'รายการโพย',
                'uri' => '',
                'group' => [
                    'yeekeechit/list-current',
                    'yeekeechit/list-history',
                    'yeekeechit/summary',
                    'blackredchit/list-current',
                    'blackredchit/list-history',
                    'blackredchit/summary',
                    'thai-shared/index',
                    'thai-shared/list-history',
                    'thai-shared/summary',
                ],
                'sub' => [
                    [
                        'title' => 'รายการโพยยี่กี่',
                        'uri' => 'yeekeechit/list-current',
                    ],
                    [
                        'title' => 'โพยหวยไทย-ต่างประเทศ',
                        'uri' => 'thai-shared/index',
                    ],
                    [
                        'title' => 'โพยดำ-แดง',
                        'uri' => 'blackredchit/list-current',
                    ],
                ],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,
            ],
            [
                'icon' => 'fa fa-life-ring',
                'title' => 'ดูผลรางวัล',
                'uri' => 'lotto/report',
                'group' => [
                    'lotto/report'
                ],
                'sub' => [],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,

            ],
            [
                'icon' => 'fa fa-phone-square',
                'title' => 'แจ้ง ฝาก/ถอน',
                'uri' => 'post-credit-transection/list-current',
                'group' => [
                    'post-credit-transection/list-current',
                    'post-credit-transection/create-topup',
                    'post-credit-transection/create-withdraw'
                ],
                'sub' => [],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,

            ],
            [
                'icon' => 'fa fa-file-audio-o',
                'title' => 'รายงานเครดิต',
                'uri' => 'credit-transection/list-current',
                'group' => [
                    'credit-transection/list-current',
                    'post-credit-transection/list-history',
                ],
                'sub' => [],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,

            ],
            [
                'icon' => 'fa fa-user-plus',
                'title' => 'ระบบแนะนำ',
                'uri' => '#',
                'group' => [
                    'recommend/index',
                    'recommend/member',
                    'recommend/income',
                    'recommend/withdraw',
                ],
                'sub' => [
                    [
                        'title' => 'ระบบแนะนำหวย',
                        'uri' => 'recommend/index',
                    ],
                    [
                        'title' => 'ระบบแนะนำดำแดง',
                        'uri' => 'recommend-blackred/index',
                    ],
                ],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,

            ],
            [
                'icon' => 'fa fa-newspaper-o',
                'title' => 'สร้างเลขชุด',
                'uri' => 'number-memo/index',
                'group' => [
                    'number-memo/index',
                ]
                // 'lotto/index'
                ,
                'sub' => [],
                'authen' => []
                // Auth::ADMIN,
                // Auth::EDITOR,
                // Auth::WRITER,

            ],
            [
                'icon' => 'fa fa-home',
                'title' => 'ประชาสัมพันธ์',
                'uri' => 'site/home',
                'group' => [
                    'site/home'
                ],
                'sub' => [],
                'authen' => []
            ],
        ];
        return $menus;
    }

    public static $menu_backend = [
        [
            'title' => 'หน้าแรก',
            'icon' => 'icon icon-home',
            'uri' => 'site/index',
            'group' => [
                'site/index'
            ],
            'sub' => [],
            'authen' => 'site',
        ],
        [
            'title' => 'สมาชิก',
            'icon' => 'icon icon-user',
            'uri' => 'members/list',
            'group' => [
                'members/list',
                'members/chit',
                'members/chit_detail',
                'members/credit',
                'members/update',
                'members/createtopup',
                'members/createwithdraw',
                'members/chit-blackred',
                'members/chit-shared',
                'members/chit-shared-detail',
                'members/waiting-approve'
            ],
            'sub' => [],
            'authen' => 'members',
            'call_function' => 'userCountNoActive',
        ],
        [
            'title' => 'โพยยี่กี้',
            'icon' => 'icon icon-inbox',
            'uri' => 'chit/list',
            'group' => [
                'chit/list',
                'chit/chit',
                'chit/chit_detail'
            ],
            'sub' => [],
            'authen' => 'chit',
        ],
        [
            'title' => 'รายการหวย',
            'icon' => 'icon icon-inbox',
            'uri' => 'thai-shared-game/list',
            'group' => [
                'thai-shared-game/list',
                'thai-shared-game/list-history',
                'thai-shared-game/detail',
                'thai-shared-game/history',
                'thai-shared-game/detail-lottery-lao-set'
            ],
            'sub' => [],
            'authen' => 'thai-shared-game',
        ],
        [
            'title' => 'รายการเดินบัญชีรายวัน',
            'icon' => 'icon icon-th',
            'uri' => 'summary/daily',
            'group' => [
                'summary/daily'
            ],
            'sub' => [],
            'authen' => 'summary-daily',
        ],
        [
            'title' => 'รายการเดินบัญชีรายเกม',
            'icon' => 'icon icon-th',
            'uri' => 'summary/game',
            'group' => [
                'summary/game'
            ],
            'sub' => [],
            'authen' => 'summary-game',
        ],
        [
            'title' => 'รายการเครดิต มาสเตอร์',
            'icon' => 'icon icon-bar-chart',
            'uri' => 'report/credit',
            'group' => [
                'report/credit'
            ],
            'sub' => [],
            'authen' => 'report-credit-master',
        ],
        [
            'title' => 'รายการเครดิต',
            'icon' => 'icon icon-bar-chart',
            'uri' => 'report/list-current',
            'group' => [
                'report/list-current',
                'report/list-history',
                'report/list-person',
            ],
            'sub' => [],
            'authen' => 'report',
        ],
        [
            'title' => 'รายการฝาก-ถอน มาสเตอร์',
            'icon' => 'icon icon-upload-alt',
            'uri' => 'post-credit-agent/index',
            'group' => [
                'post-credit-agent/index'
            ],
            'sub' => [],
            'authen' => 'post-credit-agent',
            'call_function' => 'AmountNewCreditTransectionAgent',
        ],
        [
            'title' => 'รายการฝาก-ถอน สมาชิก',
            'icon' => 'icon icon-volume-up',
            'uri' => 'post-credit-member/index',
            'group' => [
                'post-credit-member/index',
                'post-credit-member/create-topup',
                'post-credit-member/create-withdraw',
                'post-credit-member/updatestatus',
                'post-credit-member/credit'
            ],
            'call_function' => 'AmountNewCreditTransectionMember',
            'authen' => 'post-credit-member',
        ],
        [
            'title' => 'sms message',
            'icon' => 'icon icon-volume-up',
            'uri' => 'sms-message/index',
            'group' => [
                'sms-message/index',
            ],
            'authen' => 'post-credit-member',
        ],
        [
            'title' => 'สรุปยอดฝากถอน',
            'icon' => 'icon icon-paste',
            'uri' => 'summary/refill',
            'group' => [
                'summary/refill'
            ],
            'sub' => [],
            'authen' => 'summary',
        ],
        [
            'title' => 'บัญชีฝาก-ถอน',
            'icon' => 'icon icon-bar-chart',
            'uri' => 'transections/accountrefill',
            'group' => [
                'transections/accountrefill'
            ],
            'sub' => [],
            'authen' => 'transections',
        ],
        [
            'title' => 'ระบบแนะนำ',
            'icon' => 'icon icon-comments',
            'uri' => 'recommend/index',
            'group' => [
                'recommend/index'
            ],
            'sub' => [],
            'authen' => 'recommend',
        ],
        [
            'title' => 'รายการผู้ช่วย',
            'icon' => 'icon icon-thumbs-up',
            'uri' => 'assistant/list',
            'group' => [
                'assistant/list',
                'assistant/update'
            ],
            'sub' => [],
            'authen' => 'assistant',
        ],
        [
            'title' => 'log การเข้าใช้งาน',
            'icon' => 'icon icon-star',
            'uri' => 'access/index',
            'group' => [
                'access/index'
            ],
            'sub' => [],
            'authen' => 'access',
        ],
        [
            'title' => 'รายการเอเยนต์',
            'icon' => 'icon icon-briefcase',
            'uri' => 'agent/list',
            'group' => [
                'agent/list',
                'agent/update'
            ],
            'sub' => [],
            'authen' => 'agent',
        ],
        [
            'title' => 'จัดการผลยี่กี่',
            'icon' => 'icon icon-cogs',
            'uri' => 'system/manageresult',
            'group' => [
                'system/manageresult'
            ],
            'sub' => [],
            'authen' => 'system',
        ],
        [
            'title' => 'News',
            'icon' => 'icon icon-bullhorn',
            'uri' => 'page/news',
            'group' => [
                'page/news'
            ],
            'sub' => [],
            'authen' => 'page',
        ],
        [
            'title' => 'การแจ้งเตือน',
            'icon' => 'icon icon-bullhorn',
            'uri' => 'pusher/index',
            'group' => [
                'pusher/index',
                'pusher/create'
            ],
            'sub' => [],
            'authen' => 'admin',
        ],
        [
            'title' => 'Config',
            'icon' => 'icon icon-bullhorn',
            'uri' => 'admin/index',
            'group' => [
                'admin/index',
                'thai-shared/index',
                'thai-shared/create',
                'thai-shared/update',
                'thai-shared-answer-game/view',
                'thai-shared-answer-game/update',
                'thai-shared-answer-game/create',
                'limit-lottery-number-game/index',
                'limit-lottery-number-game/create',
                'limit-lottery-number-game/update',
            ],
            'sub' => [],
            'authen' => 'admin',
        ],
        [
            'title' => 'ตั้งค่าจ่ายระดับ',
            'icon' => 'icon icon-bullhorn',
            'uri' => 'setup-level-playtype/index',
            'group' => [
                'setup-level-playtype/index',
            ],
            'sub' => [],
            'authen' => 'setting',
        ],
        [
            'title' => 'การตั้งต่า',
            'icon' => 'icon icon-bullhorn',
            'uri' => 'setting/index',
            'group' => [
                'setting/index',
            ],
            'sub' => [],
            'authen' => 'setting',
        ],
    ];

    public static function Encrypt($password, $data)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = Constants::private_key;
        $secret_iv = 'Test_My_Key';
        // hash
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
        if ($user->recommend !== $output) {
            $user->recommend = $output;
            $user->save();
        }
        return $output;
    }

    public static function Decrypt($password = '', $data)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = Constants::private_key;
        $secret_iv = 'Test_My_Key';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);

        return $output;
    }

    public static function getIP()
    {
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');
        return $ip;
    }

    public function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function getPlayTypeCode($code)
    {
        return PlayType::find()->where(['code' => $code])->one();
    }

    public static function getTotal($provider, $columnName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$columnName];
        }
        return $total;
    }

    public static function permute($number)
    {
        $array = is_string($number) ? str_split($number) : $number;
        if(1 === count($array))
            return $array;
        $result = array();
        foreach($array as $key => $item)
            foreach(self::permute(array_diff_key($array, array($key => $item))) as $p)
                $result[] = $item . $p;
        return $result;
    }
}