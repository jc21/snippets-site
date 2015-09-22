<?php
namespace frontend\controllers;

use Yii;
use frontend\components\Controller;
use frontend\models\SnippetForm;


/**
 * Submit controller
 */
class SubmitController extends Controller
{
    /**
     * /submit
     *
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $model  = new SnippetForm;
        $errors = null;

        if ($this->request->method == 'POST') {
            // populate model attributes with user inputs
            $model->load($this->request->post());

            if ($model->validate()) {
                $snippetUrl = $model->save($this->member);
                if ($snippetUrl) {
                    return $this->redirect($snippetUrl);
                }
            }

            $errors = $model->errors;
        }

        return $this->render('index', ['model' => $model, 'errors' => $errors]);
    }
}
