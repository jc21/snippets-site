<?php

/**
 * Class common\components\ActiveRecord
 *
 */

namespace common\components;

use yii\db\ActiveRecord as YiiActiveRecord;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends YiiActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdTime',
                'updatedAtAttribute' => 'updatedTime',
            ],
        ];
    }
}
