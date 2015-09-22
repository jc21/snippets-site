<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Register';

?>

<h4>Why Register?</h4>
<ol>
    <li>Bookmark your favourite or most used snippets</li>
    <li>Submit your own snippets</li>
    <li>Comment on snippets</li>
</ol>

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

    <?= $form->field($model, 'name', ['inputOptions' => [
        'placeholder' => 'Name',
        'type'        => 'text',
        'autofocus'   => 'autofocus',
    ]]) ?>

    <?= $form->field($model, 'email', ['inputOptions' => [
        'placeholder' => 'Email address',
        'type'        => 'email',
    ]]) ?>

    <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password']])->passwordInput() ?>
    <?= Html::submitButton('Submit Registration', ['class' => 'btn btn-default btn-block']) ?>
    <?php ActiveForm::end() ?>
</div>

