<?php
namespace frontend\models;

use Yii;
use common\models\Member;


/**
 * LoginForm Model
 *
 * @see http://www.yiiframework.com/doc-2.0/guide-input-validation.html
 */
class LoginForm extends \yii\base\Model
{

    public $email;
    public $password;


    /**
     * Input Rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            // the email and password attributes are required
            [['email', 'password'], 'required', 'message' => 'Please provide all log in details'],

            // the email attribute should be a valid email address
            ['email', 'email', 'message' => 'Email is not a valid address'],

            // trim the email
            [['email'], 'trim'],
        ];
    }


    /**
     * Attempts to login
     *
     * @return bool
     */
    public function authenticate()
    {
        // Try to find member with this email
        $member = Member::find()
            ->where(['email' => $this->email])
            ->andWhere('password != ""')
            ->limit(1)
            ->one();

        /* @var $member Member */
        if ($member) {
            if (password_verify($this->password, $member->password)) {
                setcookie('k', $member->getCookieKey(), strtotime('+1 day'));
                Yii::$app->session->set('memberId',  $member->id);
                Yii::$app->session->set('memberKey', $member->getMemberKey());

                return true;
            }
        }
        $this->addError('email', 'Invalid Credentials');
        return false;
    }
}
