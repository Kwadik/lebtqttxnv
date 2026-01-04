<?php

namespace app\controllers;

use app\components\MailerOnPostCreation;
use app\components\SoftDeleteBehavior;
use app\models\Post;
use app\models\PostCreateForm;
use app\models\PostUpdateForm;
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
						'update' => ['GET','PUT'],
						'delete' => ['GET','DELETE'],
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
	 * Обработчик формы создания поста.
	 *
	 * @return false|string
	 * @throws HttpException|Exception
	 */
    public function actionCreate()
    {
		$model = new Post();

        if ($this->request->isPost) {

			$form = new PostCreateForm();

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
				$post->email_token = $post->generateEmailToken();
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

				MailerOnPostCreation::send($post);

				return json_encode([
					'success' => true,
					'message' => "История успешно опубликована",
				]);

			} else {
				return json_encode([
					'success' => false,
					'message' => implode('/', array_column($form->getErrors(), 'message')),
				]);
			}

        } else {
			throw new MethodNotAllowedHttpException('The request does not allowed.');
        }
    }

    /**
	 * Редактирование поста. Работает по приватной ссылке из письма с параметром token
	 *
	 * [GET] -- выводится форма редактирования
	 * [PUT] -- производится обработка и сохранение
     */
    public function actionUpdate()
    {
		$time = time();
		$errorMessage = '';
		$successMessage = '';
		$needForm = false;

		if ($this->request->isGet) {

			$token = $this->request->get('token');
			$postModel = Post::find()->where([
				'email_token' => $token,
			])->one();

			if ($postModel) {

				if ($time > strtotime('+12 hours', intval($postModel->created_at))) {
					$errorMessage = 'Редактирование поста доступно в течение 12 часов с момента публикации';

					// test
					$errorMessage = '';
					$needForm = true;

				} else {
					$needForm = true;
				}

			} else {
				$errorMessage = 'Недействительная ссылка или пост удалён';
			}

			return $this->render('update', [
				'postModel' => $postModel,
				'errorMessage' => $errorMessage,
				'successMessage' => $successMessage,
				'needForm' => $needForm,
			]);

		} elseif ($this->request->isPut) {

			$form = new PostUpdateForm();

			$token = Yii::$app->request->getBodyParam('email_token');
			$postModel = Post::find()->where([
				'email_token' => $token,
			])->one();

			if ($postModel) {

				if ($time > strtotime('+14 days', intval($postModel->created_at)) && false) {
					$errorMessage = 'Редактирование поста доступно в течение 12 часов с момента публикации';
				} else {

//					$form->load(Yii::$app->request->getBodyParams());
//					return json_encode($form->toArray());
					$needForm = true;

					if ($form->load(Yii::$app->request->getBodyParams()) && $form->validate()) {

						$postModel->content = $form->content;
						$postModel->updated_at = time();

						// Загружаем файл
						$form->imageFile = UploadedFile::getInstance($form, 'imageFile');

						// Обработка загрузки изображения
						if ($form->imageFile) {

							$imgSize = getimagesize($form->imageFile->tempName);

							if (!$imgSize || $imgSize[0] > 1500 || $imgSize[1] > 1500) {
								$errorMessage = 'Изображение каждой из сторон не должно превышать 1500px';
							} else {

								$fileName = uniqid('img_') . '.' . $form->imageFile->extension;
								$uploadPath = Yii::getAlias('@webroot/uploads/' . $fileName);

								$form->imageFile->saveAs($uploadPath);
								$postModel->image_path = '/uploads/' . $fileName;
							}
						}

						$postModel->save(false);
						$successMessage = 'Редактирование прозошло успешно';

					} else {
						$errorMessage = 'Редактирование прозошло с ошибками, данные неверны';
					}
				}

			} else {
				$errorMessage = 'Недействительная ссылка или пост удалён';
			}

			return $this->render('update', [
				'postModel' => $postModel,
				'errorMessage' => $errorMessage,
				'successMessage' => $successMessage,
				'needForm' => $needForm,
			]);

		} else {
			throw new MethodNotAllowedHttpException('The request does not allowed.');
		}
    }

    /**
     * Удаление поста. Работает по приватной ссылке из письма с параметром token
	 *
	 * [GET] -- выводится страница подтверждения удаления
	 * [DELETE] -- производится удаление после подтверждения
     */
	public function actionDelete()
	{
		$time = time();

		if ($this->request->isGet) {

			$errorMessage = '';

			$token = $this->request->get('token');
			$model = Post::find()->where([
				'email_token' => $token,
			])->one();

			if ($model) {

				if ($time > strtotime('+14 days', intval($model->created_at))) {
					$errorMessage = 'Удаление поста доступно в течение 14 дней с момента публикации';
				}

			} else {
				$errorMessage = 'Недействительная ссылка или пост уже удалён';
			}

			return $this->render('delete', [
				'model' => $model,
				'errorMessage' => $errorMessage,
			]);

		} elseif ($this->request->isDelete) {

			$errorMessage = '';

			$token = Yii::$app->request->getBodyParam('email_token');
			$model = Post::find()->where([
				'email_token' => $token,
			])->one();

			if ($model) {

				if ($time > strtotime('+14 days', intval($model->created_at))) {
					$errorMessage = 'Удаление поста доступно в течение 14 дней с момента публикации';
				} else {
					$model->delete();

					if (SoftDeleteBehavior::$result === false) {
						$errorMessage = 'Ошибка удаления';
					} else {
						return $this->redirect('/');
					}
				}

			} else {
				$errorMessage = 'Недействительная ссылка или пост уже удалён';
			}

			return $this->render('delete', [
				'model' => $model,
				'errorMessage' => $errorMessage,
			]);

		} else {
			throw new MethodNotAllowedHttpException('The request does not allowed.');
		}
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
