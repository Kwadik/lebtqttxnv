<?php

use app\models\PostUpdateForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Post $postModel */
/** @var app\models\PostUpdateForm $formModel */
/** @var yii\widgets\ActiveForm $form */
/** @var string $errorMessage */
/** @var string $successMessage */
/** @var bool $needForm */

$formModel = new PostUpdateForm();
$classes = [
	'post-update',
	'd-flex flex-column',
	'h-100',
	'justify-content-center',
	'align-items-start',
	'gap-3',
];
if (empty($needForm)) {
	$classes[] = 'align-items-center';
}

?>

<?php Pjax::begin([
	'id' => 'post-update-pjax',
	'formSelector' => '#post-update-form',
	'options' => [
	],
	'timeout' => 5000,
	'enablePushState' => false,
]);
?>
<div class="<?=implode(' ', $classes)?>">
    <h1><?=Html::encode('Редактирование публикации')?></h1>
	<?php if ($errorMessage): ?>
        <div class="error-message text-bg-danger p-3"><?=$errorMessage?></div>
	<?php endif; ?>
	<?php if ($successMessage): ?>
        <div class="error-message text-bg-success p-3"><?=$successMessage?></div>
	<?php endif; ?>
	<?php if ($needForm): ?>
		<?php $form = ActiveForm::begin([
			'id' => 'post-update-form',
			'options' => ['enctype' => 'multipart/form-data'],
			'action' => ['post/update'],
			'method' => 'PUT',
		]); ?>
		<?= Html::hiddenInput('email_token', $postModel->email_token, [
			'class' => 'd-none',
		]) ?>
		<?php if ($postModel->image_path): ?>
            <div class="mb-3">
                <img src="<?= Html::encode($postModel->image_path) ?>" class="img-fluid rounded" alt="Изображение от <?= Html::encode($postModel->author_name) ?>">
            </div>
		<?php endif; ?>
		<?= $form->field($formModel, 'content')->textarea([
		'rows' => 5,
		'value' => $postModel->content,
	]) ?>
		<?= $form->field($formModel, 'imageFile')->fileInput() ?>
        <div class="form-group">
			<?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-success']) ?>
        </div>
		<?php ActiveForm::end(); ?>
	<?php else: ?>
        <div class="buttons">
			<?= Html::a('Вернуться на главную', ['/'], ['class' => 'btn btn-success']) ?>
        </div>
	<?php endif; ?>
</div>
<?php Pjax::end(); ?>
