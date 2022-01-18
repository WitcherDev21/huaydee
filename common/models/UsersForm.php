<?php
namespace common\models;

use Yii;

/**
 * Password reset form
 */
class UsersForm extends \yii\base\Model
{
    public $password;
    public $confirm_password;
    public $user_roles_id;
    public $change_password;
    public $change_confirm_password;
    public $bank_account_name;
    public $bank_account_no;
    public $bank_id;
    public $tel;
    public $username;
    public $email;
    public $bankStatus;
    public $user_has_bank_id;

    public function rules()
    {
        return [
            [['username','email','password','confirm_password','change_password','change_confirm_password','tel'], 'trim'],
            [['email'], 'email', 'message' => "กรุณากรอกรูปแบบอีเมล์ให้ถูกต้อง"],
            ['confirm_password', 'compare', 'compareAttribute'=>'password'],
            ['change_password', 'compare', 'compareAttribute' => 'change_confirm_password'],
            [
                ['username'], 'unique',
                'targetAttribute' => 'username',
                'targetClass' => '\common\models\User',
                'message' => 'This username can not be taken.',
                'when' => function ($model) {
                    return $model->username != Yii::$app->user->identity->getUsername(); // or other function for get current username
                }
            ],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => 'User Name',
            'user_id' => 'User ID',
            'email' => 'อีเมล์',
            'tel' => 'เบอร์โทรศัพท์',
            'password' => 'รหัสผ่าน',
            'confirm_password' => 'ยืนยันรหัสผ่าน',
            'user_roles_id' => 'สิทธิ์การเข้าใช้งาน',
            'change_password' => 'เปลี่ยนรหัสผ่าน',
            'change_confirm_password' => 'ยืนยันรหัสผ่าน',
            'bank_id' => 'ธนาคาร',
            'bank_account_name' => 'ชื่อบัญชี',
            'bank_account_no' => 'เลขที่บัญชี',
            'bankStatus' => 'สถานะบัญชี',
        ];
    }

}
