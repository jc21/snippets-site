<?php
/* @var $this yii\web\View */
/* @var $latestSnippets [] */
/* @var $popularSnippets [] */

use yii\helpers\Html;

$this->title = '';


?>

<h4>Latest Snippets</h4>

<ul class="table">
    <?php
    foreach ($latestSnippets as $snippet) {
        ?>
        <li>
            <a href="/snippets/<?= Html::encode($snippet['language']['slug']) ?>/<?= Html::encode($snippet['slug']) ?>" title="View this Snippet"><?= Html::encode($snippet['name']) ?></a>
            <span class="right">
                <a href="/snippets/<?= Html::encode($snippet['language']['slug']) ?>" title="View Snippets in <?= Html::encode($snippet['language']['name']) ?>"><?= Html::encode($snippet['language']['name']) ?></a>
            </span>
        </li>
        <?php
    }
    ?>
</ul>

<p class="text-right">
    <a href="/lists/latest" title="View more Latest Snippets">View More</a>
</p>


<h4>Popular Snippets</h4>

<ul class="table">
    <?php
    foreach ($popularSnippets as $snippet) {
        ?>
        <li>
            <a href="/snippets/<?= Html::encode($snippet['language']['slug']) ?>/<?= Html::encode($snippet['slug']) ?>" title="View this Snippet"><?= Html::encode($snippet['name']) ?></a>
            <span class="right">
                <a href="/snippets/<?= Html::encode($snippet['language']['slug']) ?>" title="View Snippets in <?= Html::encode($snippet['language']['name']) ?>"><?= Html::encode($snippet['language']['name']) ?></a>
            </span>
        </li>
        <?php
    }
    ?>
</ul>

<p class="text-right">
    <a href="/lists/popular" title="View more Popular Snippets">View More</a>
</p>
