<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-meta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => '',
                'label' => 'url',
                'format' => 'raw',
                'value' => function($model){
                        $params = Json::decode($model->action_params);
                        $params = ArrayHelper::merge([$model->view], $params);
                        $url = Yii::$app->frontendUrlManager->createUrl($params);
                        return Html::a($url , $url, ['target' => '_blank']);
                    }
            ],
            'title.content:raw:Title',
            'keywords.content:raw:Keywords',
            'description.content:raw:Description',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
