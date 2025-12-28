<?php
namespace app\models;

use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
	private bool $applyNotDeleted = true;

	public function notDeleted()
	{
		return $this->andWhere(['deleted_at' => null]);
	}

	public function disableSoftDeleteFilter(): self
	{
		$this->applyNotDeleted = false;
		return $this;
	}

	public function prepare($builder)
	{
		if ($this->applyNotDeleted) {
			$this->andWhere(['deleted_at' => null]);
		}

		return parent::prepare($builder);
	}
}