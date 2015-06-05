<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'GitHub Browser',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            $navItems=[
                '<li>
                        <form id="searchform" name="searchform" method="post" action="' . Url::to(['site/search']) . '">
                          <div class="fieldcontainer">
                            <input type="text" name="query" class="searchfield" placeholder="Search project..." tabindex="1">
                            <input type="submit" name="search" id="searchbutton" value="">
                            <input type="hidden" name="_csrf" value="' . Yii::$app->request->getCsrfToken() . '">
                          </div>
                        </form>
                    </li>'
            ];
            if (Yii::$app->user->isGuest) {
                array_push(
                    $navItems, 
                    ['label' => 'Sign In', 'url' => ['/site/login']],
                    ['label' => 'Sign Up', 'url' => ['/site/signup']]
                );
            } else {
                array_push(
                    $navItems,
                    [
                        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ]
                );
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $navItems,
            ]);
            
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
