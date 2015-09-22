<?php
namespace frontend\models;

use common\components\Util;
use Yii;
use common\models\Member;
use Firebase\JWT\JWT;


/**
 * ForgotForm Model
 */
class ForgotForm extends \yii\base\Model
{

    public $email;


    /**
     * Input Rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            // the email and password attributes are required
            [['email'], 'required', 'message' => 'Please provide all fields'],

            // the email attribute should be a valid email address
            ['email', 'email', 'message' => 'Email is not a valid address'],

            // trim the email
            [['email'], 'trim'],
        ];
    }


    /**
     * Attempts to find the member and send them an email
     *
     * @return bool
     */
    public function sendEmail()
    {
        $member = Member::find()
            ->where([
                'email' => ['like', 'email', $this->email],
            ])
            ->limit(1)
            ->one();

        /* @var $member \yii\db\ActiveRecord */
        if ($member) {
            if ($member->isActive) {
                // Create a new Password Reset Key
                $member->passwordResetKey = Util::getRandomString(32);
                $member->save();

                $encrypted = JWT::encode(['i' => $member->id, 'k' => $member->passwordResetKey], Yii::$app->params['secret']);

                // Send email
                return Yii::$app->mailer->compose(['html' => 'password-reset/html', 'text' => 'password-reset/text'], [
                    'encrypted' => $encrypted,
                    'member'    => $member,
                ])
                    ->setFrom(Yii::$app->params['fromEmail'])
                    ->setTo($this->email)
                    ->setSubject('Password Reset - ' . Yii::$app->params['siteName'])
                    ->send();
            } else {
                $this->addError('email', 'You account is not active.');
            }
        } else {
            $this->addError('email', 'Could not find you!');

        }
        return false;
    }
}
