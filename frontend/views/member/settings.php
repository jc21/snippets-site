<?php
/* @var $this yii\web\View */
/* @var $member common\models\Member */
/* @var $snippetCount int */
/* @var $commentCount int */
/* @var $snippets [] */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = $this->context->member->name;

$form = ActiveForm::begin([
    'id'          => 'forgot-form',
    'options'     => [
        'class' => 'form-forgot',
    ],
    'fieldConfig' => [
        'inputOptions' => [
            'class' => 'form-control',
            //'required' => 'required',
        ]
    ],
]);
?>

<h4>Details</h4>

<div class="padded">
    <?= $form->field($model, 'name', ['inputOptions' => [
        'placeholder' => 'Name',
        'type'        => 'text',
        'autofocus'   => 'autofocus',
        'required' => 'required',
    ]]) ?>
</div>

<h4>Change Password</h4>
<p>Only specify if you want to change it</p>

<div class="padded">
    <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password', 'value' => '']])->passwordInput() ?>
    <p>&nbsp;</p>
    <?= Html::submitButton('Save', ['class' => 'btn btn-default btn-block']) ?>
</div>



<?php ActiveForm::end() ?>