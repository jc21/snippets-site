<?php
/* @var $this yii\web\View */
/* @var $list [] */
/* @var $language common\models\Language */

use yii\helpers\Html;

$this->title = 'Latest ' . ($language ? $language->name : '') . ' Snippets';

?>

<ul class="table">
    <?php
    foreach ($list['snippets'] as $snippet) {
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

<?= $list['pagination'] ?>
