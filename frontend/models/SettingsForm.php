<?php
namespace frontend\models;

use common\components\Util;
use Yii;
use common\models\Member;
use Firebase\JWT\JWT;


/**
 * SettingsForm Model
 */
class SettingsForm extends \yii\base\Model
{

    public $name;
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
            [['name'], 'required', 'message' => 'Please provide all fields'],

            // trim the email and name
            [['name'], 'trim'],
        ];
    }


    /**
     * Attempts to find the member and send them an email
     *
     * @param \common\models\Member  $member
     * @return bool
     */
    public function saveMember($member)
    {
        if ($this->password && $this->password !== $member->password) {
            $member->setRawPassword($this->password);
        }

        $member->name = $this->name;
        $member->save();

        return true;
    }


    /**
     * @return array
     */
    public function safeAttributes()
    {
        return ['name', 'password'];
    }
}
