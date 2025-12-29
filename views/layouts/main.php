<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="">
<head>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">
<?php $this->beginBody() ?>

<header id="header">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
	]);
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
		]
	]);
	NavBar::end();
	?>
</header>

<main id="main" class="flex-shrink-0 d-flex flex-column h-100 flex-shrink-0" role="main">
    <div class="container d-flex flex-column h-100">
		<?php if (!empty($this->params['breadcrumbs'])): ?>
			<?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
		<?php endif ?>
		<?= Alert::widget() ?>
		<?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Kwadik <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>
<div id="ajax-loader">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="210"
         height="210" style="shape-rendering: auto; display: block; background: transparent;"
         xmlns:xlink="http://www.w3.org/1999/xlink">
        <g>
            <g transform="rotate(0 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.4774305555555555s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(30 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.43402777777777773s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(60 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.39062499999999994s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(90 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.34722222222222215s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(120 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.3038194444444444s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(150 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.26041666666666663s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(180 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.21701388888888887s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(210 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.17361111111111108s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(240 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.13020833333333331s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(270 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.08680555555555554s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(300 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="-0.04340277777777777s" dur="0.5208333333333333s"
                             keyTimes="0;1" values="1;0" attributeName="opacity"></animate>
                </rect>
            </g>
            <g transform="rotate(330 50 50)">
                <rect fill="#ffffff" height="14" width="4" ry="4.34" rx="2" y="19" x="48">
                    <animate repeatCount="indefinite" begin="0s" dur="0.5208333333333333s" keyTimes="0;1" values="1;0"
                             attributeName="opacity"></animate>
                </rect>
            </g>
            <g></g>
        </g><!-- [ldio] generated by https://loading.io -->
    </svg>
</div>
<?php Modal::begin([
	'id' => 'modal-main',
]); ?>
<h4 class="modal-main__title"></h4>
<div class="modal-main__message"></div>
<?php Modal::end(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
