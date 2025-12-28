<?php

namespace app\controllers;

use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DebuggerController extends Controller
{
	/**
	 * @inheritDoc
	 */
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						//                        'delete' => ['POST'],
					],
				],
			]
		);
	}

	/**
	 * Lists all Post models.
	 *
	 * @return string
	 */
	public function actionIndex()
	{

		$model = new Post();
		//		$debug = $model->load([
		//			'content' => '123',
		//			'author_name' => '234',
		//			'author_email' => '456',
		////			'author_ip' => '',
		//		]);
		$model->content = '   ';
		$model->author_name = '234';
		$model->author_email = '456';

		return json_encode([
			'status' => 'ok',
			'errors' => $model->errors,
			'validate' => $model->validate(),
			'model' => $model->toArray(),
			'debug' => $model->getErrors(),
		]);
	}
}
