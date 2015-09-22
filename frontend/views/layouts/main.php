<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use kartik\growl\Growl;

/* @var $this \yii\web\View */
/* @var $content string */

$member    = &Yii::$app->controller->member;
$languages = &Yii::$app->controller->languages;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <title><?= ($this->title ? Html::encode($this->title) . ' :: ' : '') . Html::encode(Yii::$app->params['siteName']) ?></title>
    <?= Html::csrfMetaTags() ?>
    <meta name="keywords" content="snippets code functions" />
    <meta name="description" content="" />
    <meta name="Copyright" content="jc21.com 2007" />
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png" />
    <link rel="icon" href="/images/favicon.png" type="image/png" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <?php $this->head() ?>
</head>
<body id="body">
    <?php $this->beginBody() ?>

    <div id="ui">
        <div id="menu">
            <h2><a href="/" title="Go to Snippets Home">Snippets</a> <span class="smaller">v2</span></h2>

            <form name="search" method="get" action="/search" class="text-center">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search" name="query">
                </div>
            </form>

            <h4>Languages</h4>
            <ul>
                <?php
                foreach ($languages as $language) {
                    ?>
                    <li><a href="/snippets/<?= Html::encode($language['slug']) ?>" title="1 <?= Html::encode($language['name']) ?> snippets"><?= Html::encode($language['name']) ?></a></li>
                    <?php
                }
                ?>
            </ul>

            <h4>My Snippets</h4>
            <ul>
                <?php
                if ($this->context->member) {
                    ?>
                    <li><a href="/submit" title="Create a new Snippet">Create a Snippet</a></li>
                    <li><a href="/bookmarks" title="View my Bookmarks">Bookmarks</a></li>
                    <li><a href="/member/settings" title="View my Account">My Account</a></li>
                    <li><a href="/member/<?= $this->context->member->id ?>" title="View my Public Profile">My Profile</a></li>
                    <li><a href="/logout" title="Logout">Logout</a></li>
                    <?php
                } else {
                    ?>
                    <li><a href="/login" title="Login">Login</a></li>
                    <?php
                    if ($this->context->params['registrationsOpen']) {
                        ?>
                        <li><a href="/register" title="Register an Account">Register</a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>

        <div id="content">
            <h1><?= ($this->title ? Html::encode($this->title) : 'Snippets Home') ?></h1>
            <?= $content ?>
        </div>
    </div>

    <div id="footer">
        <p>&copy; jc21.com 2007</p>
    </div>

    <?php
    foreach (Yii::$app->session->getAllFlashes() as $message) {
        print Growl::widget([
            'type'          => (!empty($message['type'])) ? $message['type'] : 'danger',
            'title'         => (!empty($message['title'])) ? Html::encode($message['title']) : null,
            'icon'          => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body'          => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay'         => 1, // This delay is how long before the message shows
            'pluginOptions' => [
                'delay'     => (!empty($message['duration'])) ? $message['duration'] : 4000, // This delay is how long the message shows for
                'placement' => [
                    'from'  => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                    'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                ]
            ],
            'options' => ['style' => 'font-size:13px']
        ]);
    }
    ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php
$this->endPage();
