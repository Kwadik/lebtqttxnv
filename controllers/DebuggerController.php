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
		$provider = Post::getListProvider();

		return json_encode([
//			'getTotalCount' => $provider->getTotalCount(),
			'getModels' => array_map(function ($model) {
				return $model->toArray();
			}, $provider->getModels()),
//			'getPagination' => $provider->getPagination(),
			'query' => $provider->query,
			'pagination' => $provider->pagination,
		]);
    }
}
