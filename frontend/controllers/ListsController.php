<?php
namespace frontend\controllers;

use Yii;
use common\models\Language;
use frontend\components\Controller;
use frontend\models\LatestSnippets;
use frontend\models\PopularSnippets;


/**
 * Lists controller
 */
class ListsController extends Controller
{
    /**
     * Anonymous Actions allowed in this controller
     *
     * @var array
     */
    public $anonActions = ['latest', 'popular'];


    /**
     * /lists/latest
     *
     * @return string
     */
    public function actionLatest()
    {
        $language = null;
        $languageSlug = $this->request->getQueryParam('languageSlug');
        if ($languageSlug) {
            $language = Language::find()
                ->where(['isHidden' => 0, 'slug' => $languageSlug])
                ->limit(1)
                ->one();
        }

        return $this->render('latest', [
            'language' => $language,
            'list'     => LatestSnippets::get($language),
        ]);
    }


    /**
     * /lists/popular
     *
     * @return string
     */
    public function actionPopular()
    {
        $language = null;
        $languageSlug = $this->request->getQueryParam('languageSlug');
        if ($languageSlug) {
            $language = Language::find()
                ->where(['isHidden' => 0, 'slug' => $languageSlug])
                ->limit(1)
                ->one();
        }

        return $this->render('popular', [
            'language' => $language,
            'list'     => PopularSnippets::get($language),
        ]);
    }
}
