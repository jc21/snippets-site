<?php
/* @var $this yii\web\View */
/* @var $list [] */

use yii\helpers\Html;

$this->title = 'Bookmarks';

?>

<ul class="table">
    <?php
    foreach ($list['bookmarks'] as $bookmark) {
        if ($bookmark->snippet) {
            ?>
            <li>
                <a href="/snippets/<?= Html::encode($bookmark->snippet->language->slug) ?>/<?= Html::encode($bookmark->snippet->slug) ?>" title="View this Snippet"><?= Html::encode($bookmark->snippet->name) ?></a>
                <span class="right">
                    <a href="/snippets/<?= Html::encode($bookmark->snippet->language->slug) ?>" title="View Snippets in <?= Html::encode($bookmark->snippet->language->name) ?>"><?= Html::encode($bookmark->snippet->language->name) ?></a>
                </span>
            </li>
            <?php
        }
    }
    ?>
</ul>

<?= $list['pagination'] ?>
