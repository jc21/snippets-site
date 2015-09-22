<?php

/**
 * Class common\models\Member
 *
 * @property-read int     $id
 * @property      string  $email
 * @property      string  $password
 * @property      string  $name
 * @property      bool    $isActive
 * @property      bool    $isAdmin
 * @property      int     $lastSeenTime
 * @property      int     $createdTime
 * @property      int     $updatedTime
 * @property      string  $passwordResetKey
 *
 */

namespace common\models;

use common\components\ActiveRecord;
use common\components\TypecastBehavior;
use yii\behaviors\TimestampBehavior;

class Member extends ActiveRecord
{

    /**
     * The Mysql Table Name
     *
     * @access public
     * @return string
     */
    public static function tableName()
    {
        return 'Member';
    }


    /**
     * Typecast Behavior
     *
     * @access public
     * @return array
     */
    public function behaviors()
    {
        $behaviours = [
            'timestampBehavior' => [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdTime',
                'updatedAtAttribute' => 'updatedTime',
            ],
            'typecastBehavior' => [
                'class'  => TypecastBehavior::className(),
                'intFields' => [
                    'id',
                    'lastSeenTime',
                    'createdTime',
                    'updatedTime',
                ],
                'boolFields' => [
                    'isActive',
                    'isAdmin',
                ],
            ],
        ];
        return array_merge(parent::behaviors(), $behaviours);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSnippets()
    {
        return $this->hasMany(Snippet::className(), ['memberId' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(SnippetComment::className(), ['memberId' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(Bookmark::className(), ['memberId' => 'id']);
    }


    /**
     * getMemberKey
     * Uses the session id of the member to get a unique key for the member object
     *
     * @return string
     */
    public function getMemberKey()
    {
        if ($this->id) {
            return md5($this->id . '_' . session_id() . '_' . __CLASS__);
        }

        return '-';
    }


    /**
     * validateMemberKey
     * Checks that the member key provided matches the key generated for this member
     *
     * @param  $memberKey
     * @return bool
     */
    public function validateMemberKey($memberKey)
    {
        if ($this->id && $memberKey == $this->getMemberKey()) {
            return true;
        }

        return false;
    }


    /**
     * getCookieKey
     * Returns a key for a cookie variable that can be used to log this member straight in
     *
     * @return null|string
     */
    public function getCookieKey()
    {
        if ($this->id) {
            return md5($this->email) . '|' . $this->id . '|' . md5($this->createdTime);
        }

        return null;
    }


    /**
     * loadFromCookieKey
     * Load this member from the cookie key
     *
     * @param  $cookieKey
     * @return bool|null|static
     */
    public static function loadFromCookieKey($cookieKey)
    {
        if ($cookieKey) {
            $items = explode('|', $cookieKey);
            if (count($items) == 3) {
                $emailEncoded       = array_shift($items);
                $memberId           = array_shift($items);
                $createdTimeEncoded = array_shift($items);

                /** @var $member Member */
                $member = self::findOne($memberId);
                if ($member && md5($member->email) == $emailEncoded && md5($member->createdTime) == $createdTimeEncoded) {
                    return $member;
                }
            }
        }

        return false;
    }


    /**
     * Sets a members password, you must save() after this call
     *
     * @param string $password
     * @return void
     */
    public function setRawPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
    }
}
