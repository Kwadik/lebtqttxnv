<?php

namespace app\controllers;

use app\components\MailerOnPostCreation;
use app\models\Post;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\mail\BaseMailer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mail;
use yii\web\Response;

class DebuggerController extends Controller
{
	public $enableCsrfValidation = false;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
//				'authenticator' => [
//					'optional' => [
//						'index',
//						'test',
//					],
//				],
				'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
//                        'delete' => ['POST'],
                        'test' => ['DELETE', 'POST'],
//                        'test' => ['POST'],
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
//		$provider = Post::getListProvider();

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
			'debug' => 123,
		]);

		if ($this->request->isDelete) {
			return json_encode($this->request->post());
		}

		$model = Post::find()->where([
			'email_token' => '7a97dbd6574c25d077a7ae865a632618',
		])->one();
		return json_encode($model ? $model->toArray() : $model);
    }

    /**
     * Lists all Post models.
     *
     * @return string
     */
    public function actionTest()
    {

		if ($this->request->isDelete) {
			return json_encode(Yii::$app->request->headers->get('Token'));
		}

		$model = Post::find()->where([
			'email_token' => '7a97dbd6574c25d077a7ae865a632618',
		])->one();
		return json_encode($model ? $model->toArray() : $model);
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
