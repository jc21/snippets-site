<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */

$this->title = 'Error ' . $exception->statusCode;

?>
<div class="site-error">
    <p><?= nl2br(Html::encode($exception->getMessage())) ?></p>
</div>
