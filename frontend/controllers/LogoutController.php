<?php
namespace frontend\controllers;

use Yii;
use frontend\components\Controller;


/**
 * Logout controller
 */
class LogoutController extends Controller
{

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {

        // Remove cookie
        setcookie('k', '0', time());

        // Reset Session
        $this->session->destroy();
        return $this->goHome();
    }
}
