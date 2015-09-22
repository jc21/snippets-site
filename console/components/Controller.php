<?php

namespace console\components;

use yii\console\Controller as YiiController;
use yii\helpers\Console;

/**
 * Find and Update Photos
 */
class Controller extends YiiController
{

    public $color = true;


    /**
     * printLine
     *
     * @access public
     * @param  string  $message
     * @param  string  $color
     * @param  bool    $noDate
     * @return void
     */
    public function printLine($message, $color = null, $noDate = false)
    {
        if (!$noDate) {
            print '[' . date('Y-m-d H:i:s') . ']  ';
        }

        if ($this->color && $color) {
            print $this->ansiFormat($message, $color);
        } else {
            print $message;
        }

        print PHP_EOL;
    }
}
