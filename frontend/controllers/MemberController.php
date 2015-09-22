<?php
namespace frontend\controllers;

use frontend\models\SettingsForm;
use Yii;
use common\models\Member;
use common\models\Snippet;
use common\models\SnippetComment;
use frontend\models\MemberSnippetsList;
use frontend\components\Controller;


/**
 * Member controller
 */
class MemberController extends Controller
{
    /**
     * Anonymous Actions allowed in this controller
     *
     * @var array
     */
    public $anonActions = ['view'];


    /**
     * Site Homepage
     *
     * @return string
     */
    public function actionView()
    {
        $memberId = (int) $this->request->getQueryParam('id');
        if (!$memberId) {
            return $this->goHome();
        }

        $member = Member::find()
            ->where(['id' => $memberId])
            ->limit(1)
            ->one();

        if (!$member) {
            return $this->goHome();
        }

        $snippetCount = Snippet::find()
            ->where(['memberId' => $member->id, 'isHidden' => 0])
            ->count();

        $commentCount = SnippetComment::find()
            ->where(['memberId' => $member->id])
            ->count();

        return $this->render('view', [
            'member'       => $member,
            'snippetCount' => $snippetCount,
            'commentCount' => $commentCount,
            'snippets'     => MemberSnippetsList::get($member),
        ]);
    }


    /**
     * /member/settings
     *
     * @return string
     */
    public function actionSettings()
    {
        $model  = new SettingsForm;
        $errors = null;

        $model->attributes = $this->member->attributes;

        if ($this->request->method == 'POST') {
            $model->load($this->request->post());

            if ($model->validate() && $model->saveMember($this->member)) {
                $this->notifySuccess('Account Updated');
                return $this->redirect($_SERVER['REQUEST_URI']);
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }

        return $this->render('settings', ['model' => $model, 'errors' => $errors]);
    }
}
