<?php
namespace app\components;

use app\models\Post;

/**
 * Компонент для контроля частоты публикации постов
 */
class PostRateLimiter {

	/**
	 * Режим ограничения публикаций только по IP
	 */
	const STRATEGY_IP = 1;
	/**
	 * Режим ограничения публикаций только по имейлу
	 */
	const STRATEGY_EMAIL = 2;
	/**
	 * Комбинированный режим ограничения публикаций по имейлу
	 */
	const STRATEGY_BOTH = 3;

	/**
	 * Дефолтный режим
	 * Можно изменить в файле конфигурации приложения
	 *
	 * @var int
	 */
	public $strategy = self::STRATEGY_BOTH;
	/**
	 * Дефолтный инервал времени ограничения
	 * Можно изменить в файле конфигурации приложения
	 *
	 * @var int
	 */
	public $intervalSeconds = 180;

	/**
	 * Проверяет на доступность публикации.
	 * Возвращает true если публикация доступна, либо количество секунд до разблокировки
	 *
	 * @param $email
	 * @param $ip
	 * @return bool|int
	 */
	public function check($email, $ip)
	{
		$query = Post::find();

		if ($this->strategy === self::STRATEGY_IP) {
			$query->andWhere(['author_ip' => $ip]);
		} elseif ($this->strategy === self::STRATEGY_EMAIL) {
			$query->andWhere(['author_email' => $email]);
		} else {
			$query->andWhere([
				'or',
				['author_email' => $email],
				['author_ip' => $ip]
			]);
		}

		$lastPost = $query->orderBy(['created_at' => SORT_DESC])->one();

		if (!$lastPost) {
			return true;
		}

		$since = time() - $lastPost->created_at;

		if ($since < $this->intervalSeconds) {
			return $this->intervalSeconds - $since;
		}

		return true;
	}
}
