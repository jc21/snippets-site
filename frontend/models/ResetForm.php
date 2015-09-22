<?php
namespace frontend\models;

use Yii;


/**
 * ResetForm Model
 */
class ResetForm extends \yii\base\Model
{

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
            [['password'], 'required', 'message' => 'Please provide all fields'],
        ];
    }


    /**
     * Attempts to find the member and send them an email
     *
     * @param  \common\models\Member  $member
     * @return bool
     */
    public function save($member)
    {
        if (strlen($this->password) < 8) {
            $this->addError('password', 'Password must be at least 8 characters');
        } else {
            $member->setRawPassword($this->password);
            $member->passwordResetKey = '';
            return $member->save();
        }

    }
}
