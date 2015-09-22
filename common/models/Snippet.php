<?php

/**
 * Class common\models\Snippet
 *
 * @property-read int     $id
 * @property      string  $slug
 * @property      string  $name
 * @property      string  $description
 * @property      string  $code
 * @property      int     $languageId
 * @property      int     $memberId
 * @property      int     $views
 * @property      int     $downloads
 * @property      bool    $isHidden
 * @property      int     $createdTime
 * @property      int     $updatedTime
 *
 */

namespace common\models;

use common\components\ActiveRecord;
use common\components\TypecastBehavior;
use yii\behaviors\TimestampBehavior;

class Snippet extends ActiveRecord
{

    /**
     * The Mysql Table Name
     *
     * @access public
     * @return string
     */
    public static function tableName()
    {
        return 'Snippet';
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
                    'languageId',
                    'memberId',
                    'views',
                    'downloads',
                    'createdTime',
                    'updatedTime',
                ],
                'boolFields' => [
                    'isHidden',
                ],
            ],
        ];
        return array_merge(parent::behaviors(), $behaviours);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languageId']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'memberId']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(SnippetComment::className(), ['snippetId' => 'id']);
    }
}
