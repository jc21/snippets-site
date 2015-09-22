<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\Json;

class TypecastBehavior extends Behavior
{

    const TYPE_INT   = 1;
    const TYPE_BOOL  = 2;
    const TYPE_FLOAT = 3;
    const TYPE_JSON  = 4;

    /**
     * @var array  Integer fields
     */
    public $intFields = [];

    /**
     * @var array  Boolean fields
     */
    public $boolFields = [];

    /**
     * @var array  Float fields
     */
    public $floatFields = [];

    /**
     * @var array  JSON fields
     */
    public $jsonFields = [];



    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND    => 'afterFind',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE  => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE  => 'afterSave',
        ];
    }


    /**
     * Make sure fields are type hinted when we find them
     *
     * @param $event
     */
    public function afterFind($event)
    {
        if (count($this->intFields)) {
            $this->toInteger($this->intFields, $event->sender);
        }

        if (count($this->boolFields)) {
            $this->toBool($this->boolFields, $event->sender);
        }

        if (count($this->floatFields)) {
            $this->toFloat($this->floatFields, $event->sender);
        }

        if (count($this->jsonFields)) {
            $this->toJsonDecoded($this->jsonFields, $event->sender);
        }
    }


    /**
     * Make sure fields are in database format before save
     *
     * @param $event
     */
    public function beforeSave($event)
    {
        // Bools to Ints
        if (count($this->boolFields)) {
            $this->toInteger($this->boolFields, $event->sender);
        }

        // Array to json encoded
        if (count($this->jsonFields)) {
            $this->toJsonEncoded($this->jsonFields, $event->sender);
        }
    }


    /**
     * Convert fields back to the afterFind values after we have saved teh record
     *
     * @param $event
     */
    public function afterSave($event)
    {
        $this->afterFind($event);
    }


    /**
     * toInteger
     * Types the values for the specified fields to integers
     *
     * @param  array        $fieldNames
     * @param  ActiveRecord $record
     * @return void
     *
     */
    protected function toInteger($fieldNames, &$record)
    {
        foreach ($fieldNames as $fieldName) {
            $record->$fieldName = (int) $record->$fieldName;
        }
    }


    /**
     * toBool
     * Types the values for the specified fields to booleans
     *
     * @param  array        $fieldNames
     * @param  ActiveRecord $record
     * @return void
     *
     */
    protected function toBool($fieldNames, &$record)
    {
        foreach ($fieldNames as $fieldName) {
            $record->$fieldName = (bool) $record->$fieldName;
        }
    }


    /**
     * toFloat
     * Types the values for the specified fields to floats
     * In case you're wondering, in PHP: 'float', 'double' and 'real' are all the same.
     *
     * @param  array        $fieldNames
     * @param  ActiveRecord $record
     * @return void
     *
     */
    protected function toFloat($fieldNames, &$record)
    {
        foreach ($fieldNames as $fieldName) {
            $record->$fieldName = (float) $record->$fieldName;
        }
    }

    /**
     * Decode a json encoded value, defaults to array implementation
     *
     * @param array         $fieldNames
     * @param ActiveRecord  $record
     */
    protected function toJsonDecoded($fieldNames, &$record)
    {
        foreach ($fieldNames as $fieldName) {
            $value              = Json::decode($record->$fieldName, true);
            $record->$fieldName = $value && is_array($value) ? $value : array();
        }
    }

    /**
     * JSON encode a array
     *
     * @param array         $fieldNames
     * @param ActiveRecord  $record
     */
    protected function toJsonEncoded($fieldNames, &$record)
    {
        foreach ($fieldNames as $fieldName) {
            // Don't re-encode if already encoded
            if (is_array($record->$fieldName)) {
                $record->$fieldName = Json::encode($record->$fieldName);
            }
        }
    }
}
