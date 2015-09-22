<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Language;

$this->title = 'Create a Snippet';

?>

<p>Here you can submit your snippets for everyone to see. If this is not your code, please be sure to credit those responsible. All fields are required.</p>


<h4>Submission</h4>

<?php
$form = ActiveForm::begin([
    'id'          => 'snippet-form',
    'options'     => [
        'class' => 'form',
    ],
    'fieldConfig' => [
        'inputOptions' => [
            'class'    => 'form-control',
            'required' => 'required',
        ]
    ],
]);

?>

<div class="form-group">
    <label for="languageId">Language</label>
    <?= Html::activeDropDownList($model, 'languageId',
        ArrayHelper::map(Language::find()->orderBy('name ASC')->all(), 'id', 'name'), ['class' => 'form-control', 'id' => 'languageId']) ?>
</div>

<?= $form->field($model, 'name', ['inputOptions' => [
    'type'        => 'text',
    'required'    => 'required',
    'placeholder' => 'Title of your Snippet',
]]) ?>

<?= $form->field($model, 'description')->textArea([
    'rows'        => '4',
    'placeholder' => 'Github style markdown is allowed',
    'required'    => 'required',
]) ?>

<?= $form->field($model, 'code')->textArea([
    'rows'        => '12',
    'placeholder' => '',
    'required'    => 'required',
]) ?>


<div class="text-center">
    <?= Html::submitButton('Save Snippet', ['class' => 'btn btn-default']) ?>
</div>
<?php ActiveForm::end() ?>
