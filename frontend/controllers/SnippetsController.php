<?php
namespace frontend\controllers;

use Yii;
use common\models\Bookmark;
use common\models\Language;
use common\models\Snippet;
use frontend\components\Controller;
use frontend\models\CommentForm;
use frontend\models\LatestSnippets;
use frontend\models\PopularSnippets;


/**
 * Snippets controller
 */
class SnippetsController extends Controller
{
    /**
     * Anonymous Actions allowed in this controller
     *
     * @var array
     */
    public $anonActions = ['index', 'download'];

    /**
     * @var \yii\db\ActiveRecord
     */
    protected $language = null;

    /**
     * @var \yii\db\ActiveRecord
     */
    protected $snippet  = null;


    /**
     * /snippets/language-slug[/snippet-slug]
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->parseQueryParams(true);

        // Make sure at least language slug is there
        if (!$this->language) {
            return $this->goHome();
        }

        if ($this->snippet) {
            $this->snippet->updateCounters(['views' => 1]);

            return $this->render('snippet', [
                'language'     => $this->language,
                'snippet'      => $this->snippet,
                'commentModel' => new CommentForm,
            ]);
        } else {
            return $this->render('language', [
                'language'        => $this->language,
                'latestSnippets'  => LatestSnippets::getTopTen($this->language),
                'popularSnippets' => PopularSnippets::getTopTen($this->language),
            ]);
        }
    }


    /**
     * /snippets/language-slug/snippet-slug/download
     *
     * @return string
     */
    public function actionDownload()
    {
        if (!$this->parseQueryParams()) {
            return $this->goHome();
        }

        $this->snippet->updateCounters(['downloads' => 1]);

        $text  = '==============================================================================' . "\r\n";
        $text .= $this->snippet->name . '  -  ' . $this->params['siteName'] . "\r\n";
        $text .= "------------------------------------------------------------------------------\r\n";
        $text .= wordwrap($this->snippet->description) . "\r\n";
        $text .= '==============================================================================' . "\r\n\r\n";

        $text .= $this->snippet->code;

        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: application/text');
        header('Content-Disposition: attachment; filename="' . $this->snippet->slug . '.txt"');
        print $text;
        exit();
    }


    /**
     * /snippets/language-slug/snippet-slug/addbookmark
     *
     * @return string
     */
    public function actionAddbookmark()
    {
        if (!$this->parseQueryParams()) {
            return $this->goHome();
        }

        Bookmark::deleteAll(['memberId' => $this->member->id, 'snippetId' => $this->snippet->id]);
        $bookmark = new Bookmark;
        $bookmark->snippetId = $this->snippet->id;
        $bookmark->memberId  = $this->member->id;
        $bookmark->save();

        $this->notifySuccess('This snippet has been added to your Bookmarks.');
        return $this->redirect('/snippets/' . $this->language->slug . '/' . $this->snippet->slug);
    }


    /**
     * /snippets/language-slug/snippet-slug/removebookmark
     *
     * @return string
     */
    public function actionRemovebookmark()
    {
        if (!$this->parseQueryParams()) {
            return $this->goHome();
        }

        Bookmark::deleteAll(['memberId' => $this->member->id, 'snippetId' => $this->snippet->id]);
        $this->notifySuccess('This snippet has been removed from your Bookmarks.');
        return $this->redirect('/snippets/' . $this->language->slug . '/' . $this->snippet->slug);
    }


    /**
     * /snippets/language-slug/snippet-slug/comment
     *
     * @return string
     */
    public function actionComment()
    {
        if (!$this->parseQueryParams()) {
            return $this->goHome();
        }

        $model = new CommentForm;

        if ($this->request->method == 'POST') {

            // populate model attributes with user inputs
            $model->load($this->request->post());

            if ($model->validate() && $model->save($this->member, $this->snippet)) {
                $this->notifySuccess('Your comment has been added.');
            } else {
                $this->notifyError('Could not add your comment.');
            }
        }

        return $this->redirect('/snippets/' . $this->language->slug . '/' . $this->snippet->slug);
    }


    /**
     * @param  bool  $skipSnippetCheck
     * @return bool
     */
    protected function parseQueryParams($skipSnippetCheck = false)
    {
        $languageSlug = $this->request->getQueryParam('languageSlug');
        $snippetSlug = $this->request->getQueryParam('snippetSlug');

        // Make sure at least language slug is there
        if (!$languageSlug) {
            return $this->goHome();
        }

        // Find the language row
        $this->language = Language::find()->where(['slug' => $languageSlug])->limit(1)->one();
        if (!$this->language) {
            return false;
        }


        // Make sure at least language slug is there
        if (!$snippetSlug) {
            if ($skipSnippetCheck) {
                return true;
            }
            return $this->goHome();
        }

        $this->snippet = Snippet::find()->where([
            'slug' => $snippetSlug,
            'languageId' => $this->language->id,
        ])->limit(1)->one();

        if (!$this->snippet && !$skipSnippetCheck) {
            return false;
        }

        return true;
    }
}
