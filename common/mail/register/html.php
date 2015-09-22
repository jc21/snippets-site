<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

$siteName = Yii::$app->params['siteName'];

?>

<table bgcolor="#3D3E42" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td align="center">
            <table border="0" width="479" cellpadding="0" cellspacing="0" align="center" bgcolor="#4F5056">
                <thead>
                <tr>
                    <td>
                        <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/images/email_head.gif" border="0" width="479" height="16" alt="" />
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <table border="0" width="100%" cellpadding="7">
                            <tbody>
                            <tr>
                                <td>
                                    <p>
                                        <font size="4" color="#f29900"><span style="color: #f29900; font-family: Arial;"> Registration Confirmation </span></font>
                                    </p>
                                    <p>
                                        <font size="2"><span style="color: rgb(255, 255, 255); font-family: Arial;"> Hi <?= Html::encode($member->name) ?>, click this link to complete your <?= $siteName ?> registration:</span></font>
                                    </p>
                                    <p>
                                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/register/complete?k=<?= urlencode($encrypted) ?>"><font size="4" color="#f29900"><span style="color: #f29900; font-family: Arial;">Click here to complete registration</span></font></a>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>
                        <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/images/email_foot.gif" border="0" width="479" height="16" alt="" />
                    </td>
                </tr>
                </tfoot>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    </tbody>
</table>
