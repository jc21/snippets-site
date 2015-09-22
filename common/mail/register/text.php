<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

Registration Confirmation
======================
Hi <?= $member->name ?>, click this link to complete your <?= $siteName ?> registration:

http://<?= $_SERVER['HTTP_HOST'] ?>/login/reset?k=<?= urlencode($encrypted) ?>"

