<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Teachers extends \common\models\db\Teachers implements \yii\web\IdentityInterface {
	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;
	const IS_TEMP_PASSWORD = 1;
	const ISNOT_TEMP_PASSWORD = 0;
	
    // add timestamp behavior
	public function behaviors()
	{
	    return [
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created_at',
	            'updatedAtAttribute' => 'updated_at',
	            'value' => new Expression('NOW()'),
	        ],
	    ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['phone_number', 'first_name', 'last_name'], 'required'],
			[['address', 'qrcode'], 'string'],
			[['gender', 'university_id', 'photo_id', 'status'], 'default', 'value' => null],
			[['gender', 'university_id', 'photo_id', 'status'], 'integer'],
			[['dob', 'created_at', 'updated_at'], 'safe'],
			[['phone_number'], 'string', 'max' => 32],
			[['first_name', 'last_name'], 'string', 'max' => 128],
			[['email'], 'string', 'max' => 256],
			[['code'], 'string', 'max' => 16],
			[['phone_number'], 'unique'],
			[['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
			[['university_id'], 'exist', 'skipOnError' => true, 'targetClass' => Universities::className(), 'targetAttribute' => ['university_id' => 'id']],
			[['dob'], 'date', 'format' => 'php:Y-m-d'],
			['phone_number', function ($attribute, $params, $validator) {
				if (!\common\helpers\PhoneHelper::isPhoneNumber($this->$attribute)) {
					$this->addError($attribute, Yii::t('app', 'Phone number invalid'));
				}
			}],
			['email', 'email'],
			['gender', function ($attribute, $params, $validator) {
				if (
					$this->$attribute != \common\models\Teachers::GENDER_MALE
					&& $this->$attribute != \common\models\Teachers::GENDER_FEMALE
				) {
					$this->addError($attribute, Yii::t('app', 'Gender invalid'));
				}
			}],
		];
	}

	// identity interface
	/**
	 * {@inheritdoc}
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => ACTIVE]);
	}

	/**
	 * {@inheritdoc}
	 * @param \Lcobucci\JWT\Token $token
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		return static::findOne(['id' => intval($token->getClaim('uid')), 'status' => ACTIVE]);
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => ACTIVE]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status' => ACTIVE,
		]);
	}

	/**
	 * Finds user by verification email token
	 *
	 * @param string $token verify email token
	 * @return static|null
	 */
	public static function findByVerificationToken($token)
	{
		return static::findOne([
			'verification_token' => $token,
			'status' => INACTIVE
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return bool
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		return $timestamp + $expire >= time();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	public function generateEmailVerificationToken()
	{
		$this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}
}