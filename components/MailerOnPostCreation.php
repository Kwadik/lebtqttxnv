<?php
namespace app\components;

use app\models\Post;
use Yii;
use yii\symfonymailer\Mailer;

/**
 * Компонент для отправки писем при публикации
 */
class MailerOnPostCreation {

	public static ?Mailer $mailer = null;

	/**
	 * Отправляет письмо с ссылками на редактирование и удаление поста
	 */
	public static function send($model)
	{
		if (empty(self::$mailer)) {
			self::$mailer = Yii::$app->mailer;
		}

		return self::$mailer->compose('//post/email', [
			'model' => $model,
		])
			->setFrom('no-reply@storyvalut.com')
			->setTo($model->author_email)
			->setSubject('Успешная публикация истории')
//			->setTextBody('Текст сообщения')
//			->setHtmlBody('<b>текст сообщения в формате HTML</b>')
			->send();
	}
}
