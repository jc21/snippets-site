<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';

?>

<div id="login-wrapper">
    <?php
    $form = ActiveForm::begin([
        'id'          => 'login-form',
        'options'     => [
            'class' => 'form-signin',
        ],
        'fieldConfig' => [
            'inputOptions' => [
                'class' => 'form-control',
                'required' => 'required',
            ]
        ],
    ]);

    ?>

    <?= $form->field($model, 'email', ['inputOptions' => [
        'placeholder' => 'Email address',
        'type'        => 'email',
        'autofocus'   => 'autofocus',
    ]]) ?>

    <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password']])->passwordInput() ?>
    <?= Html::submitButton('Log in', ['class' => 'btn btn-default btn-block']) ?>
    <?php ActiveForm::end() ?>

    <p>&nbsp;</p>
    <p class="text-center"><a href="/login/forgot">Forgot your password?</a></p>
</div>
