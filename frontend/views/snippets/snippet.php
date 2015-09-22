<?php
/* @var $this yii\web\View */
/* @var $language common\models\Language */
/* @var $snippet common\models\Snippet */

use yii\helpers\Html;
use cebe\markdown\GithubMarkdown;
use \common\models\SnippetComment;
use \common\models\Bookmark;
use yii\bootstrap\ActiveForm;

$this->title = $snippet->name;

$geshi = new GeSHi($snippet->code, $language->renderCode);
$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
$geshi->set_header_type(GESHI_HEADER_DIV);
$geshi->set_tab_width(4);
$geshiCode = $geshi->parse_code();

$markdown                 = new GithubMarkdown();
$markdown->html5          = true;
$markdown->enableNewlines = true;

$comments = SnippetComment::find()
    ->where(['snippetId' => $snippet->id])
    ->with('member')
    ->orderBy('createdTime ASC')
    ->all();

$hasBookmarked = false;
if ($this->context->member) {
    $hasBookmarked = Bookmark::find()->where(['memberId' => $this->context->member->id, 'snippetId' => $snippet->id])->count();
}

if (!$this->context->member && $this->context->params['registrationsOpen']) {
    ?>
    <span class="bookmark">
        <a href="/login?r=<?= $_SERVER['REQUEST_URI'] ?>" title="Login and return here">Login</a>
        or
        <a href="/register" title="Register an account">Register</a> to Bookmark this snippet
    </span>
    <?php
} else if ($this->context->member) {
    // We are logged in
    ?>
    <span class="bookmark">
        <?php
        if ($hasBookmarked) {
            ?>
            <span class="bookmark_minus"><a href="/snippets/<?= Html::encode($language->slug) ?>/<?= Html::encode($snippet->slug) ?>/removebookmark" title="Remove Snippet from Bookmarks">Remove from Bookmarks</a></span>
            <?php
        } else {
            ?>
            <span class="bookmark_add"><a href="/snippets/<?= Html::encode($language->slug) ?>/<?= Html::encode($snippet->slug) ?>/addbookmark" title="Add Snippet to Bookmarks">Add to Bookmarks</a></span>
            <?php
        }
        ?>
    </span>
    <?php
}
?>

<h4>Description
    <span class="right">
        <a href="/snippets/<?= Html::encode($language->slug) ?>" title="View Snippets in <?= Html::encode($language->name) ?>"><?= Html::encode($language->name) ?></a>
    </span>
</h4>

<p><?= $markdown->parse($snippet->description) ?></p>


<h4>The Code
    <span class="right">
        <a href="/snippets/<?= Html::encode($language->slug) ?>/<?= Html::encode($snippet->slug) ?>/download" title="Download this snippet as a Text File">Download</a>
    </span>
</h4>

<div class="code"><?= $geshiCode ?></div>


<h4>Credits</h4>

<p>Added by <a href="/member/<?= Html::encode($snippet->member->id) ?>" title="View <?= Html::encode($snippet->member->name) ?>'s Profile"><strong><?= Html::encode($snippet->member->name) ?></strong></a> on <?= date('jS F Y', $snippet->createdTime) ?></p>


<h4>Comments</h4>

<?php
if ($comments) {
    ?>
    <dl>
        <?php
        foreach ($comments as $comment) {
            ?>
            <dt>
                <a href="/member/<?= Html::encode($comment->member->id) ?>/"><?= Html::encode($comment->member->name) ?></a>
                <span><?= date('jS F Y, g:i a', $comment->createdTime) ?></span>
            </dt>
            <dd><?= $markdown->parse($comment->comment) ?></dd>
            <?php
        }
        ?>
    </dl>
    <?php
} else {
    ?>
    <p>There are no comments about this snippet.</p>
    <?php
}
?>



<h4>Post Comment <span class="right">Github Markdown Supported</span></h4>

<?php
if (!$this->context->member) {
    ?>
    <p>You must be logged in to post a comment.</p>
    <p><a href="/login?r=<?= $_SERVER['REQUEST_URI'] ?>" title="Login and post your comment">Login here</a> to post a comment</p>
    <?php
} else {
    // $commentModel
    ?>

    <?php
    $form = ActiveForm::begin([
        'id'          => 'login-form',
        'action'   => '/snippets/' . $language->slug . '/' . $snippet->slug . '/comment',
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

    <div class="form-group">
        <?= $form->field($commentModel, 'comment')->textArea([
            'rows'     => '2',
            'class'    => 'form-control',
            'required' => 'required',
        ]) ?>
    </div>

    <div class="text-right">
        <input type="submit" class="btn btn-default btn-xs" value="Post Comment">
    </div>
    <?php
    ActiveForm::end();
}
?>
