<?php
namespace frontend\controllers;

use Yii;
use frontend\components\Controller;
use frontend\models\SearchSnippets;


/**
 * Search controller
 */
class SearchController extends Controller
{
    /**
     * Anonymous Actions allowed in this controller
     *
     * @var array
     */
    public $anonActions = ['index'];


    /**
     * Site Homepage
     *
     * @return string
     */
    public function actionIndex()
    {
        $list  = false;
        $query = trim($this->request->getQueryParam('query'));

        if (strlen($query) > 3) {
            $list = SearchSnippets::get($query);
        }

        return $this->render('index', [
            'query' => $query,
            'list'  => $list,
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
