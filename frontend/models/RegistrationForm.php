<?php

namespace frontend\models;

use common\models\UserHasBank;
use dektrium\user\models\RegistrationForm as BaseRegistrationForm;
use Yii;

class RegistrationForm extends BaseRegistrationForm
{
    public $password_repeat;
    public $tel;
    public $bank_id;
    public $bank_account_name;
    public $bank_account_no;
    public $agent;
    public $user_status;
    
    public function rules()
    {
        $user = $this->module->modelMap['User'];

        $rules = array_merge(parent::rules(), [
            [['tel', 'bank_id', 'bank_account_name', 'bank_account_no'], 'required', 'message' => 'ห้ามมีค่าว่าง'],
            'usernameRequired' => ['username', 'required', 'message' => 'ห้ามมีค่าว่าง'],
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword, 'message' => 'ห้ามมีค่าว่าง'],
            'usernameUnique'   => [
                'username',
                'unique',
                'targetClass' => $user,
                'message' => 'username นี้ได้ถูกใช้ไปแล้ว'
            ],
            'usernamePattern'  => [
                'username',
                'match',
                'pattern' => $user::$usernameRegexp,
                'message' => 'ห้ามใช้อักขระพิเศษ กรุณากรอกหนังสือและตัวเลข เท่านั้น'
            ],
            [['tel'], 'match', 'pattern' => '/^\s*-?[0-9]{10}\s*$/', 'message' => 'กรุณากรอกให้ถูกต้อง'],
            [['agent', 'user_status'], 'safe'],
            ['bank_account_no', 'number', 'message' => 'กรุณากรอกเฉพาะคัวเลขเท่านั้น'],
            'passwordLength' => ['password', 'string', 'min' => 8, 'max' => 72, 'tooShort' => 'กรุณากรอก password ให้มีความยาวมากกว่า 8 ตัวอักษร', 'tooLong' => 'กรุณากรอก password ให้มีความยาน้อยกว่า 72 ตัวอักษร'],
            'passwordReLength' => ['password_repeat', 'string', 'min' => 8, 'max' => 72, 'tooShort' => 'กรุณากรอก password ให้มีความยาวมากกว่า 8 ตัวอักษร', 'tooLong' => 'กรุณากรอก password ให้มีความยาน้อยกว่า 72 ตัวอักษร'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'password ไม่ match กัน'],
            ['password', 'validatePassword'],
            [['bank_account_no'], 'unique',
                'targetClass' => UserHasBank::ClassName() ,
                'targetAttribute' => ['bank_account_no'],
                'message' => 'เลขที่บัญชีนี้ได้ถูกใช้ไปแล้ว'
            ]
        ]);
        unset($rules['emailRequired']);
        return $rules;
    }

    public function validatePassword()
    {
        if ($this->username === $this->password) {
            return $this->addError('password', 'password ต้องไม่ซ้ำกับ username');
        }
        return;
    }
}