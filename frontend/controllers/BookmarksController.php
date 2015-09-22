<?php
namespace frontend\controllers;

use Yii;
use frontend\components\Controller;
use frontend\models\BookmarkList;


/**
 * Bookmarks controller
 */
class BookmarksController extends Controller
{
    /**
     * Anonymous Actions allowed in this controller
     *
     * @var array
     */
    public $anonActions = [];


    /**
     * /bookmarks
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'list' => BookmarkList::get($this->member),
        ]);
    }
}
