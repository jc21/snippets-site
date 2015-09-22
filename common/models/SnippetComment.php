<?php

/**
 * Class common\models\SnippetComment
 *
 * @property-read int     $id
 * @property      int     $memberId
 * @property      int     $snippetId
 * @property      string  $comment
 * @property      int     $createdTime
 * @property      int     $updatedTime
 *
 */

namespace common\models;

use common\components\ActiveRecord;
use common\components\TypecastBehavior;
use yii\behaviors\TimestampBehavior;

class SnippetComment extends ActiveRecord
{

    /**
     * The Mysql Table Name
     *
     * @access public
     * @return string
     */
    public static function tableName()
    {
        return 'SnippetComment';
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
                    'memberId',
                    'snippetId',
                    'createdTime',
                    'updatedTime',
                ],
            ],
        ];
        return array_merge(parent::behaviors(), $behaviours);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSnippet()
    {
        return $this->hasOne(Snippet::className(), ['id' => 'snippetId']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'memberId']);
    }
}
