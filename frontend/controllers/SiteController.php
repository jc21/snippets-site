<?php
namespace frontend\controllers;

use Yii;
use frontend\components\Controller;
use frontend\models\LatestSnippets;
use frontend\models\PopularSnippets;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * Anonymous Actions allowed in this controller
     *
     * @var array
     */
    public $anonActions = ['index', 'error'];


    /**
     * Site Homepage
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'latestSnippets'  => LatestSnippets::getTopTen(),
            'popularSnippets' => PopularSnippets::getTopTen(),
        ]);
    }


    /**
     * Error Page
     *
     * @return string
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        return $this->render('error', ['exception' => $exception]);
    }
}
