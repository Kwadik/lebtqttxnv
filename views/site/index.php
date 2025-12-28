<?php

/** @var yii\web\View $this */

use app\models\Post;
use yii\data\ActiveDataProvider;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-8">
				<?=$this->render('@app/views/post/_postList', [
					'dataProvider' => Post::getListProvider(),
				])?>
            </div>
            <div class="col-lg-4">
				<?=$this->render('//post/form')?>
            </div>
        </div>
    </div>
</div>
