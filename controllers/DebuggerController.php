<?php

namespace app\controllers;

use app\components\MailerOnPostCreation;
use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\mail\BaseMailer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mail;

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
		$provider = Post::getListProvider();

//		return json_encode([
////			'getTotalCount' => $provider->getTotalCount(),
//			'getModels' => array_map(function ($model) {
//				return $model->toArray();
//			}, $provider->getModels()),
////			'getPagination' => $provider->getPagination(),
//			'query' => $provider->query,
//			'pagination' => $provider->pagination,
//		]);
		return json_encode([
			'post/update' => Url::toRoute([
				'post/update',
				'token' => 123,
			], 'http'),
		]);
    }

    /**
     * Lists all Post models.
     *
     * @return string
     */
    public function actionEmail()
    {
		$post = Post::findOne([
			'id' => 10,
		]);

//		return $this->render('//post/email', [
//			'model' => $post,
//		]);
		return json_encode([
			'result' => MailerOnPostCreation::send($post),
			'fileTransportPath' => MailerOnPostCreation::$mailer->fileTransportPath,
			'$post' => $post->toArray(),
		]);
    }
}
