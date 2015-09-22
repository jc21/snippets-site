<?php
/* @var $this yii\web\View */
/* @var $member common\models\Member */
/* @var $snippetCount int */
/* @var $commentCount int */
/* @var $snippets [] */

use yii\helpers\Html;

if (!$this->context->member || $this->context->member->id != $member->id) {
    $this->title = 'Member: ' . $member->name;
} else {
    $this->title = 'My Profile';
}

?>

<h4>Stats</h4>
<ul class="table">
    <li>Registered:         <span class="right"><?= date('jS F Y', $member->createdTime) ?></span></li>
    <li>Last Login:         <span class="right"><?= date('jS F Y', $member->lastSeenTime) ?></span></li>
    <li>Submitted Snippets: <span class="right"><?= number_format($snippetCount, 0) ?></span></li>
    <li>Comments Posted:    <span class="right"><?= number_format($commentCount, 0) ?></span></li>
</ul>

<h4>Submitted Snippets</h4>
<ul class="table">
    <?php
    foreach ($snippets['snippets'] as $snippet) {
        ?>
        <li>
            <a href="/snippets/<?= Html::encode($snippet->language->slug) ?>/<?= Html::encode($snippet->slug) ?>" title="View this Snippet"><?= Html::encode($snippet->name) ?></a>
            <span class="right">
                <a href="/snippets/<?= Html::encode($snippet->language->slug) ?>" title="View Snippets in <?= Html::encode($snippet->language->name) ?>"><?= Html::encode($snippet->language->name) ?></a>
            </span>
        </li>
        <?php
    }
    ?>
</ul>

<?= $snippets['pagination'] ?>

