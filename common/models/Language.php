<?php

/**
 * Class common\models\Language
 *
 * @property-read int     $id
 * @property      string  $slug
 * @property      string  $name
 * @property      string  $renderCode
 * @property      bool    $isHidden
 * @property      int     $createdTime
 * @property      int     $updatedTime
 *
 */

namespace common\models;

use common\components\ActiveRecord;
use common\components\TypecastBehavior;
use yii\behaviors\TimestampBehavior;

class Language extends ActiveRecord
{

    /**
     * The Mysql Table Name
     *
     * @access public
     * @return string
     */
    public static function tableName()
    {
        return 'Language';
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
    public function getSnippets()
    {
        return $this->hasMany(Snippet::className(), ['languageId' => 'id']);
    }
}
