<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset your password';

?>

<div id="login-wrapper">
    <?php
    $form = ActiveForm::begin([
        'id'          => 'reset-form',
        'options'     => [
            'class' => 'form-reset',
        ],
        'fieldConfig' => [
            'inputOptions' => [
                'class' => 'form-control',
                'required' => 'required',
            ]
        ],
    ]);

    ?>

    <?= $form->field($model, 'password', ['inputOptions' => [
        'placeholder' => 'Password',
        'autofocus'   => 'autofocus',
    ]])->passwordInput() ?>

    <?= Html::submitButton('Save Password', ['class' => 'btn btn-default btn-block']) ?>
    <?php ActiveForm::end() ?>
</div>

