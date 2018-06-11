<?php

namespace app\models;

use yii\helpers\Security;
use yii\web\IdentityInterface;
use yii;

/**
 * This is the model class for table "users".
 *
 * @property string $userid
 * @property string $username
 * @property string $email
 * @property string $name
 * @property string $surname
 * @property string $password_hash
 * @property integer $active
 * @property integer $new_password
 *
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{
    public $new_password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['username', 'password_hash','email','name','surname'], 'string', 'max' => 100],
            [['password_reset_token','new_password'], 'safe'],
            [['active'], 'integer'],
            [['email', 'username'], 'email'],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => Yii::t('general', 'id'),
            'username' => Yii::t('general', 'username'),
            'password_hash' => Yii::t('general', 'password'),
            'active' => Yii::t('general', 'active'),
            'email' => Yii::t('general', 'email'),
            'new_password' => Yii::t('general', 'new_password')
        ];
    }
    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /* removed
        public static function findIdentityByAccessToken($token)
        {
            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        }
    */
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password_hash)
    {
        return $this->password_hash === sha1($password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password_hash)
    {
        $this->password_hash = Security::generatePasswordHash($password_hash);
    }

    public function resetPassword($password_hash){
        $this->password_hash = sha1($password_hash);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {

        $this->auth_key = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function findByResetToken($token){
        return User::find()->where(['password_reset_token'=>$token])->one();
    }

    public function findByAuthToken($token){
        return static::findOne(['auth_key' => $token]);
    }
    /** EXTENSION MOVIE **/

}