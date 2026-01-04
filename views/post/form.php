<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\models\PostCreateForm;

/** @var yii\web\View $this */
/** @var app\models\PostCreateForm $model */
/** @var yii\widgets\ActiveForm $form */

$model = new PostCreateForm();

?>

<div class="post-form">
    <?php $form = ActiveForm::begin([
		'id' => 'post-create-form',
		'options' => ['enctype' => 'multipart/form-data'],
		'action' => ['post/create'],
	]); ?>
	<?= $form->field($model, 'author_name')->textInput() ?>
	<?= $form->field($model, 'author_email')->textInput() ?>
	<?= $form->field($model, 'content')->textarea(['rows' => 5]) ?>
	<?= $form->field($model, 'imageFile')->fileInput() ?>
	<?= $form->field($model, 'captcha')->widget(Captcha::class, [
		'imageOptions' => [
			'id' => 'post-create-captcha',
        ],
	]); ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
