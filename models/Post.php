<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $content
 * @property string $author_name
 * @property string $author_email
 * @property string $author_ip
 * @property int $created_at
 * @property int|null $deleted_at
 * @property int $updated_at
 */
class Post extends ActiveRecord
{

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'posts';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'softDelete' => [
				'class' => \app\components\SoftDeleteBehavior::class,
				'attribute' => 'deleted_at',
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function fields()
	{
		return [
			'id',
			'content',
			'author_name',
			'author_email',
			'author_ip',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['author_name','author_email'],'required'],
			['content','match','pattern'=>'/\S/',
				'message'=>'Сообщение не может состоять только из пробелов.'],
			['content','trim'],
			['content','string','min'=>5,'max'=>1000],
			// Очистка content с разрешёнными тегами
			['content', 'filter', 'filter' => function ($value) {
				return \yii\helpers\HtmlPurifier::process($value, [
					'HTML.Allowed' => 'b,i,s',
				]);
			}],
			['author_name','string','min'=>2,'max'=>15],
			['author_email','email',
				'message'=>'Некорректный e-mail'],
			['image_path','string','max'=>255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function find()
	{
		return (new PostQuery(get_called_class()))->notDeleted();
	}

	/**
	 * {@inheritdoc}
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if ($insert) {
			Yii::$app->postStats->increment($this->author_ip);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		// вызывается только если произошло мягкое удаление
		Yii::$app->postStats->decrement($this->author_ip);
	}
}
