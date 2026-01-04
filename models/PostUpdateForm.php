<?php

namespace app\models;

use yii\base\Model;
use yii\captcha\Captcha;
use yii\helpers\FormatConverter;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;

/**
 * This is the model class for ActiveForm.
 *
 * @property string $token
 * @property string $content
 * @property UploadedFile|string|null $imageFile
 */
class PostUpdateForm extends Model {

	public string|null $content = null;
	public UploadedFile|string|null $imageFile = null;

	public function rules() {

		return [
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
			['imageFile','file','extensions'=>['jpg','jpeg','png','webp'],'maxSize'=>2*1024*1024,'skipOnEmpty'=>true],
		];
	}

	public function attributeLabels()
	{
		return [
			'content'      => 'Сообщение',
			'imageFile'    => 'Изображение'
		];
	}
}
