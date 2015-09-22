<?php
/* @var $this yii\web\View */
/* @var $latestSnippets [] */
/* @var $popularSnippets [] */
/* @var $language common\models\Language */

use yii\helpers\Html;

$this->title = $language->name . ' Snippets';

?>

<h4>Latest in <?= Html::encode($language->name) ?></h4>

<ul class="table">
    <?php
    foreach ($latestSnippets as $snippet) {
        ?>
        <li>
            <a href="/snippets/<?= Html::encode($language->slug) ?>/<?= Html::encode($snippet['slug']) ?>" title="View this Snippet"><?= Html::encode($snippet['name']) ?></a>
        </li>
        <?php
    }
    ?>
</ul>

<p class="text-right">
    <a href="/lists/latest/<?= Html::encode($language->slug) ?>" title="View more Latest Snippets">View More</a> &nbsp;
</p>


<h4>Popular in <?= Html::encode($language->name) ?></h4>

<ul class="table">
    <?php
    foreach ($popularSnippets as $snippet) {
        ?>
        <li>
            <a href="/snippets/<?= Html::encode($language->slug) ?>/<?= Html::encode($snippet['slug']) ?>" title="View this Snippet"><?= Html::encode($snippet['name']) ?></a>
        </li>
        <?php
    }
    ?>
</ul>

<p class="text-right">
    <a href="/lists/popular/<?= Html::encode($language->slug) ?>" title="View more Popular Snippets">View More</a> &nbsp;
</p>
