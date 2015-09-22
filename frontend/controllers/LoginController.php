<?php
namespace frontend\controllers;

use Yii;
use common\models\Member;
use frontend\components\Controller;
use frontend\models\ForgotForm;
use frontend\models\ResetForm;
use frontend\models\LoginForm;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;


/**
 * Login controller
 */
class LoginController extends Controller
{

    public $anonActions = ['index', 'forgot', 'reset'];

    /**
     * /login
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if ($this->member) {
            return $this->goHome();
        }

        // Check for login
        $model = new LoginForm;

        $redirectAfter = $this->request->getQueryParam('r');
        if ($redirectAfter) {
            $this->session->set('redirectAfterLogin', $redirectAfter);
        }

        $errors = null;
        if ($this->request->method == 'POST') {
            // populate model attributes with user inputs
            $model->load($this->request->post());

            if ($model->validate() && $model->authenticate()) {
                // all inputs are valid
                $redirectAfter = $this->session->get('redirectAfterLogin');
                if ($redirectAfter) {
                    $this->session->remove('redirectAfterLogin');
                    $this->redirect($redirectAfter);
                } else {
                    return $this->goHome();
                }
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }

        return $this->render('login', ['model' => $model, 'errors' => $errors]);
    }


    /**
     * /login/forgot
     *
     * @return string|\yii\web\Response
     */
    public function actionForgot()
    {
        if ($this->member) {
            return $this->goHome();
        }

        // Check for login
        $model = new ForgotForm;

        $errors = null;
        if ($this->request->method == 'POST') {
            // populate model attributes with user inputs
            $model->load($this->request->post());

            if ($model->validate() && $model->sendEmail()) {
                $this->notifySuccess('An email has been sent to ' . $model->email);
                return $this->redirect('/login');
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }

        return $this->render('forgot', ['model' => $model, 'errors' => $errors]);
    }


    /**
     * /login/reset
     *
     * @return string|\yii\web\Response
     */
    public function actionReset()
    {
        if ($this->member) {
            return $this->goHome();
        }

        $errors    = null;
        $encrypted = $this->request->getQueryParam('k');

        if ($encrypted) {
            try {
                $data = JWT::decode($encrypted, $this->params['secret'], ['HS256']);

                // Get member
                $member = Member::find()
                    ->where([
                        'id' => $data->i,
                    ])
                    ->limit(1)
                    ->one();

                if ($member) {
                    if ($member->isActive) {
                        if ($member->passwordResetKey && $member->passwordResetKey == $data->k) {
                            $model = new ResetForm;
                            if ($this->request->method == 'POST') {
                                // populate model attributes with user inputs
                                $model->load($this->request->post());

                                if ($model->validate() && $model->save($member)) {
                                    $this->notifySuccess('Your new password has been saved. Please log in.');
                                    return $this->redirect('/login');
                                } else {
                                    // validation failed: $errors is an array containing error messages
                                    $errors = $model->errors;
                                }
                            }

                            return $this->render('reset', ['model' => $model, 'errors' => $errors]);
                        } else {
                            $this->notifyError('That password reset link has expired');
                        }
                    } else {
                        $this->notifyError('Account is not active');
                    }
                } else {
                    $this->notifyError('Invalid Account');
                }
            } catch (SignatureInvalidException $e) {
                $this->notifyError('Invalid password reset key');
            }
        } else {
            $this->notifyError('Invalid password reset key');
        }

        return $this->redirect('/login');
    }
}
