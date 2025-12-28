<?php

namespace app\controllers;

use app\models\Post;
use app\models\PostForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

	public function actionIndex()
	{
		die('ok');
	}

	/**
	 * Lists all Post models.
	 *
	 * @return string
	 */
	public function actionList()
	{
		$query = Post::find()->orderBy(['created_at' => SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Post::$listPortionLength,
			],
		]);

		return $this->render('_postList', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Lists all Post models.
	 *
	 * @return string
	 */
	public function actionList1()
	{
		$query = Post::find()->orderBy(['created_at' => SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Post::$listPortionLength,
			],
		]);

		return $this->getView()->render('_postList', [
			'dataProvider' => $dataProvider,
		], $this);
	}

    /**
     * Displays a single Post model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	/**
	 * Обработчик формы создания поста.
	 *
	 * @return false|string
	 * @throws HttpException|Exception
	 */
    public function actionCreate()
    {
		$model = new Post();

        if ($this->request->isPost) {

			$form = new PostForm();

			if ($form->load(Yii::$app->request->post()) && $form->validate()) {

				// Проверка ограничения публикации
				$ip = Yii::$app->request->userIP;
				$limitCheck = Yii::$app->postLimiter->check($form->author_email, $ip);

				if ($limitCheck !== true) {
					//$form->addError('content', "Вы можете опубликовать следующее сообщение через $limitCheck сек.");
					return json_encode([
						'success' => false,
						'field' => 'content',
						'message' => "Вы можете опубликовать следующее сообщение через $limitCheck сек.",
					]);
				}

				// Загружаем файл
				$form->imageFile = UploadedFile::getInstance($form, 'imageFile');

				$post = new Post();
				$post->content = $form->content;
				$post->author_name = $form->author_name;
				$post->author_email = $form->author_email;
				$post->author_ip = $ip;
				$post->created_at = time();
				$post->updated_at = time();

				// Обработка загрузки изображения
				if ($form->imageFile) {

					$imgSize = getimagesize($form->imageFile->tempName);

					if (!$imgSize || $imgSize[0] > 1500 || $imgSize[1] > 1500) {

						//$form->addError('imageFile', 'Изображение каждой из сторон не должно превышать 1500px.');
						return json_encode([
							'success' => false,
							'field' => 'imageFile',
							'message' => "Изображение каждой из сторон не должно превышать 1500px",
						]);
					}

					$fileName = uniqid('img_') . '.' . $form->imageFile->extension;
					$uploadPath = Yii::getAlias('@webroot/uploads/' . $fileName);

					$form->imageFile->saveAs($uploadPath);
					$post->image_path = '/uploads/' . $fileName;
				}

				$post->save(false);

				return json_encode([
					'success' => true,
					'field' => 'imageFile',
					'message' => "Пост $post->id успешно опубликован",
					'list' => Post::getListProvider(),
					'debug' => Yii::$app->request->post(),
				]);

			} else {
				return json_encode([
					'success' => false,
					'field' => 'content',
					'message' => "test",
					'getErrors' => $form->getErrors(),
				]);
				throw new HttpException(401, implode('/', array_column($form->getErrors(), 'message')));
			}

        } else {
			throw new MethodNotAllowedHttpException('The request does not allowed.');
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
