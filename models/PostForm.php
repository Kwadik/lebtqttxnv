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
 * @property int $id
 * @property string $content
 * @property string $author_name
 * @property string $author_email
 * @property string $author_ip
 * @property Captcha $captcha
 */
class PostForm extends Model {

	public string $content;
	public string $author_name;
	public string $author_email;
	public Captcha $captcha;
	public string $imageFile;

	public function rules() {

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
			['author_email','email'],
			['captcha','captcha'],
			['imageFile','file','extensions'=>['jpg','jpeg','png','webp'],'maxSize'=>2*1024*1024,'skipOnEmpty'=>true],
		];
	}

	public function attributeLabels()
	{
		return [
			'content'      => 'Сообщение',
			'author_name'  => 'Имя автора',
			'author_email' => 'Email',
			'captcha'      => 'Капча',
			'imageFile'    => 'Изображение'
		];
	}
}
