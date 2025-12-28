<?php

/**
 * @var $model app\models\Post
 */

use yii\helpers\Html;
use app\components\IpMaskHelper;

$formatter = Yii::$app->formatter;

// Количество сообщений по этому же IP
$postCount = Yii::$app->postStats->getPostCountByIp($model->author_ip);

?>

<div class="card mb-3 shadow-sm">
	<div class="card-body">
		<?php if ($model->image_path): ?>
            <div class="mb-3">
                <img src="<?= Html::encode($model->image_path) ?>" class="img-fluid rounded" alt="Изображение от <?= Html::encode($model->author_name) ?>">
            </div>
		<?php endif; ?>
		<h5 class="card-title mb-1">
			<?= Html::encode($model->author_name) ?>
		</h5>
        <div class="card-text mb-1">
			<?= $model->content ?>
        </div>
		<div class="text-muted small mb-2">
			<?= implode(' | ', [
				$formatter->asRelativeTime($model->created_at),
				IpMaskHelper::mask($model->author_ip),
				$model::pluralQuantity($postCount),
//				"Сообщений от автора: $postCount",
//				Yii::t('app', '{n, plural, one{# пост} few{# поста} many{# постов}}', [
//					'n' => $postCount,
//				]),
            ]) ?>
		</div>
	</div>
</div>
