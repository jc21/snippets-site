<?php
namespace frontend\models;

use common\components\Util;
use Yii;
use common\models\Member;
use Firebase\JWT\JWT;


/**
 * RegisterForm Model
 */
class RegisterForm extends \yii\base\Model
{

    public $name;
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
            [['email', 'name', 'password'], 'required', 'message' => 'Please provide all fields'],

            // the email attribute should be a valid email address
            ['email', 'email', 'message' => 'Email is not a valid address'],

            // trim the email and name
            [['email', 'name'], 'trim'],
        ];
    }


    /**
     * Attempts to find the member and send them an email
     *
     * @return bool
     */
    public function register()
    {
        $member = Member::find()
            ->where([
                'email' => ['like', 'email', $this->email],
            ])
            ->limit(1)
            ->one();

        if (!$member) {
            // Create a new member
            $member = new Member;
            $member->email            = $this->email;
            $member->name             = $this->name;
            $member->isActive         = 0;
            $member->passwordResetKey = Util::getRandomString(32);

            $member->setRawPassword($this->password);
            $member->save();

            $encrypted = JWT::encode(['i' => $member->id, 'k' => $member->passwordResetKey], Yii::$app->params['secret']);

            // Send email
            return Yii::$app->mailer->compose(['html' => 'register/html', 'text' => 'register/text'], [
                'encrypted' => $encrypted,
                'member'    => $member,
            ])
                ->setFrom(Yii::$app->params['fromEmail'])
                ->setTo($this->email)
                ->setSubject('Registration Confirmation - ' . Yii::$app->params['siteName'])
                ->send();
        } else {
            $this->addError('email', 'An account already exists for ' . $this->email);
        }
        return false;
    }
}
