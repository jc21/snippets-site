<?php
namespace frontend\components;

use common\models\Language;
use Yii;
use yii\web\Controller as YiiController;
use common\models\Member;


/**
 * Controller Component
 *
 * @property \yii\web\Session $session
 */
class Controller extends YiiController
{
    /**
     * @var Member $member
     */
    public $member = null;

    /**
     * @var array $anonActions
     */
    public $anonActions = [];

    /**
     * @var array $params
     */
    public $params = [];

    /**
     * @var \yii\web\Request $request
     */
    public $request = null;

    /**
     * @var \yii\web\Session $session
     */
    public $session = null;

    /**
     * @var \yii\caching\ApcCache $cache
     */
    public $cache = null;

    /**
     * @var array
     */
    public $languages = null;


    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $this->params  = &Yii::$app->params;
        $this->request = &Yii::$app->request;
        $this->session = &Yii::$app->session;
        $this->cache   = &Yii::$app->cache;

        if ($this->session->isActive) {
            $this->session->open();
        }

        $this->loadLanguages();
        if ($this->loadMember()) {
            $this->member->lastSeenTime = time();
            $this->member->save();
        }
    }


    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        if ($this->session->isActive) {
            $this->session->close();
        }

        return parent::afterAction($action, $result);
    }


    /**
     * @param string $id
     * @param array $params
     * @return mixed|\yii\web\Response
     * @throws \yii\base\InvalidRouteException
     */
    public function runAction($id, $params = [])
    {
        // See if it's ok to view this controller->action anonymously
        $resolvedActionName = ($id ? $id : $this->defaultAction);

        if (!$this->member && !in_array($resolvedActionName, $this->anonActions)) {
            // nope
            $this->session->set('redirectAfterLogin', $_SERVER['REQUEST_URI']);
            return $this->redirect('/login');
        } else {
            return parent::runAction($id, $params);
        }
    }


    /**
     * loadMember
     *
     * @return bool
     */
    protected function loadMember()
    {
        // See if session has member
        if ($this->session->get('memberId') && $this->session->get('memberKey')) {
            // Try to load from Session
            $memberId  = (int) $this->session->get('memberId');
            $memberKey = $this->session->get('memberKey');
            $member    = Member::findOne($memberId);

            if ($member && $member->validateMemberKey($memberKey) && $member->isActive) {
                // OK
                $this->member = $member;
                return true;
            }
        }

        // See if we need to load from Cookie
        if (isset($_COOKIE['k'])) {
            $cookieKey = $_COOKIE['k'];
            $member    = Member::loadFromCookieKey($cookieKey);

            if ($member && $member->isActive) {
                $this->member = $member;
                return true;
            }
        }

        return false;
    }


    /**
     * loadLanguages
     *
     * @return bool
     */
    protected function loadLanguages()
    {
        $cacheKey  = Yii::$app->params['cacheKeys']['languages'];
        $languages = $this->cache->get($cacheKey);

        if (!$languages) {
            $languages = [];

            $result = Language::find()
                ->where(['isHidden' => 0])
                ->orderBy('name')
                ->all();

            foreach ($result as $language) {
                if (count($language->snippets)) {
                    $languages[$language->id] = $language->attributes;
                    $languages[$language->id]['count'] = count($language->snippets);
                }
            }

            $this->cache->set($cacheKey, $languages, 600);
        }

        $this->languages = $languages;
    }


    /**
     * Add a Success Flash Notification
     *
     * @param string  $message
     * @param string  $fontawesomeIcon
     */
    public function notifySuccess($message, $fontawesomeIcon = 'fa-check')
    {
        $this->session->setFlash('success', [
            'type'     => 'success',
            'icon'     => 'fa ' . $fontawesomeIcon,
            'message'  => $message,
        ]);
    }


    /**
     * Add a Error Flash Notification
     *
     * @param string  $message
     * @param string  $fontawesomeIcon
     */
    public function notifyError($message, $fontawesomeIcon = 'fa-times')
    {
        $this->session->setFlash('error', [
            'type'     => 'danger',
            'duration' => 10000,
            'icon'     => 'fa ' . $fontawesomeIcon,
            'message'  => $message,
        ]);
    }
}
