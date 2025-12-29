<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Post $model */
/** @var string $errorMessage */

?>

<div class="post-delete d-flex flex-column h-100 align-items-center justify-content-center gap-3">
    <h1><?=Html::encode('Удаление публикации')?></h1>
    <?php if ($errorMessage): ?>
    <div class="error-message text-bg-danger p-3"><?=$errorMessage?></div>
    <div class="buttons">
		<?= Html::a('Вернуться на главную', ['/'], ['class' => 'btn btn-success']) ?>
    </div>
    <?php else: ?>
		<?php $form = ActiveForm::begin([
			'id' => 'post-delete-form',
			'options' => ['data-token' => $model->email_token],
			'action' => ['post/delete'],
			'method' => 'DELETE',
		]); ?>
		<?= Html::hiddenInput('email_token', $model->email_token, [
			'class' => 'd-none',
		]) ?>
        <div class="form-group">
			<?= Html::submitButton('Удалить публикацию', ['class' => 'btn btn-success']) ?>
        </div>
		<?php ActiveForm::end(); ?>
	<?php endif; ?>
</div>
