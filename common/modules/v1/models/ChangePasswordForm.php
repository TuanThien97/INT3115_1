<?php

namespace common\modules\v10\models;

use Yii;

class ChangePasswordForm extends \yii\base\Model
{
    public $password;
    public $confirm_password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['confirm_password'], 'required'],
            [['password'], 'string', 'min' => 6],
            [['password'], 'string', 'max' => 20],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Mật khẩu mới',
            'confirm_password' => 'Xác nhận mật khẩu mới',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword()
    {
        if ($this->password !== $this->confirm_password) {
            $this->addError('password', Yii::t('app', 'Password and Confirm password is not matched'));
        }
    }
}
