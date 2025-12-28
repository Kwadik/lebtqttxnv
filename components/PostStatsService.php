<?php
namespace app\components;

use Yii;

/**
 * Компонент счетчика количества публикаций по IP c кэшированием
 */
class PostStatsService {

	/**
	 * Префикс для ключа
	 */
	public static string $key_prefix = 'post_count_ip_';

	/**
	 * Увеличивает счётчик постов по IP
	 */
	public function increment(string $ip): int
	{
		return Yii::$app->redis->incr(self::$key_prefix . $ip);
	}

	/**
	 * Уменьшает счётчик постов по IP
	 */
	public function decrement(string $ip): int
	{
		return Yii::$app->redis->decr(self::$key_prefix . $ip);
	}

	/**
	 * Возвращает количество постов по IP
	 */
	public function getPostCountByIp(string $ip): int
	{
		$value = Yii::$app->redis->get(self::$key_prefix . $ip);
		return (int)$value;
	}
}
