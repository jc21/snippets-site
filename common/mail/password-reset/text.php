<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

Password Reset Request
======================
Hi <?= $member->name ?>, here is your password reset link for <?= $siteName ?>

http://<?= $_SERVER['HTTP_HOST'] ?>/login/reset?k=<?= urlencode($encrypted) ?>"

