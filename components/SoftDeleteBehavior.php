<?php
namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Компонент поведения мягкого удаления в модели Post
 */
class SoftDeleteBehavior extends Behavior {

	public string $attribute = 'deleted_at';
	public static $result;

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_DELETE => 'softDelete',
		];
	}

	/**
	 * Вместо удаления — ставим timestamp
	 */
	public function softDelete($event)
	{
		$this->owner->{$this->attribute} = time();
		self::$result = $this->owner->save(false, [$this->attribute]);

		// отменяем физическое удаление
		$event->isValid = false;
	}

	/**
	 * "Хард" удаление, если будет нужно
	 */
	public function hardDelete()
	{
		$this->owner->deleteInternal();
	}
}
