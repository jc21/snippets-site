<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forgot your password';

?>

<p>Enter your registered email address and we'll send you an email for you to reset your password.</p>

<div id="login-wrapper">
    <?php
    $form = ActiveForm::begin([
        'id'          => 'forgot-form',
        'options'     => [
            'class' => 'form-forgot',
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

    <?= Html::submitButton('Send Email', ['class' => 'btn btn-default btn-block']) ?>
    <?php ActiveForm::end() ?>

    <p>&nbsp;</p>
    <p class="text-center"><a href="/login">Go back</a></p>
</div>

