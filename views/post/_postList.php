<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var $dataProvider yii\data\ActiveDataProvider */

$posts = $dataProvider->getModels();
$offset = $dataProvider->pagination->getOffset();
$limit = $dataProvider->pagination->getLimit();
$page = $dataProvider->pagination->getPage();
$counterFrom = 1 + $offset;
$counterTo = min($dataProvider->getTotalCount(), $limit * ($page + 1));

?>
<?php Pjax::begin([
	'id' => 'post-list-pjax',
	'options' => [
		'class' => 'post-list-pjax-kwa',
	],
	'timeout' => 5000,
	'enablePushState' => false,
]);
?>
<div class="counters mb-1">Показано записей <?=$counterFrom?>-<?=$counterTo?> из <?=$dataProvider->getTotalCount()?></div>
<?php if ($posts): ?>
	<?php foreach ($posts as $post): ?>
		<?=$this->render('_postItem', [
			'model' => $post,
		])?>
	<?php endforeach; ?>
<?php else: ?>
    Пока ещё ничего не публиковалось
<?php endif; ?>
<?php //if ($page + 1 < $dataProvider->pagination->getPageCount()): ?>
<?php //endif; ?>
<?php Pjax::end(); ?>
    <div class="text-center mt-3">
        <button id="load-more" class="btn btn-success">Показать ещё</button>
    </div>
<?php $js = <<<JS
let currentPage = 1;
let maxPage = {$dataProvider->pagination->pageCount};

$('#load-more').on('click', function() {
    if (currentPage + 1 >= maxPage) {
        $(this).hide();
    }

    $.pjax.reload({
        timeout: 5000,
        container: '#post-list-pjax',
        url: '/post/list' + '?page=' + (++currentPage),
        replace: false,
        push: false,
        scrollTo: false
    });
});
JS;

$this->registerJs($js);
