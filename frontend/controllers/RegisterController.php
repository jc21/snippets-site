<?php
namespace frontend\controllers;

use Yii;
use common\models\Member;
use frontend\components\Controller;
use frontend\models\RegisterForm;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;


/**
 * Register controller
 */
class RegisterController extends Controller
{

    public $anonActions = ['index', 'complete'];

    /**
     * /register
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if ($this->member) {
            return $this->goHome();
        }

        if (!$this->params['registrationsOpen']) {
            $this->notifyError('Registrations are currently closed');
            return $this->goHome();
        }

        $model = new RegisterForm;

        $errors = null;
        if ($this->request->method == 'POST') {
            // populate model attributes with user inputs
            $model->load($this->request->post());

            if ($model->validate() && $model->register()) {
                $this->notifySuccess('Complete your registration by opening the email sent to ' . $model->email);
                return $this->redirect('/login');
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }

        return $this->render('index', ['model' => $model, 'errors' => $errors]);
    }


    /**
     * /register/complete
     *
     * @return string|\yii\web\Response
     */
    public function actionComplete()
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
                    if (!$member->isActive) {
                        if ($member->passwordResetKey && $member->passwordResetKey == $data->k) {
                            $member->passwordResetKey = '';
                            $member->isActive = 1;
                            $member->save();
                            $this->notifySuccess('Your registration is complete. Please log in.');

                            return $this->redirect('/login');
                        } else {
                            $this->notifyError('That registration link is terrible!');
                        }
                    } else {
                        $this->notifySuccess('Your registration is already complete. Please log in.');
                    }
                } else {
                    $this->notifyError('Invalid Account');
                }
            } catch (SignatureInvalidException $e) {
                $this->notifyError('Invalid registration key');
            }
        } else {
            $this->notifyError('Invalid registration key');
        }

        return $this->redirect('/login');
    }
}
