<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Post $model */

?>

<div class="post-email">
    <h2><?=Html::encode('Успешная публикация истории')?></h2>
    <p><a href="<?=Url::toRoute([
			'post/update',
			'token' => $model->email_token,
		], 'http')?>"><?=Html::encode('Ссылка на редактирование')?></a></p>
    <p><a href="<?=Url::toRoute([
			'post/delete',
			'token' => $model->email_token,
		], 'http')?>"><?=Html::encode('Ссылка на удаление')?></a></p>
</div>
