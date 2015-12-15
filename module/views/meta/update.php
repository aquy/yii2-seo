<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$params = Json::decode($page['action_params']);
$params = ArrayHelper::merge([$page['view']], $params);
$url = Yii::$app->frontendUrlManager->createUrl($params);

$this->title = 'Редактировать сео: ' . ' ' . $url;
$this->params['breadcrumbs'][] = ['label' => 'Сео', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="seo-meta-update">

    <h1>Сео для страницы <?= Html::a($url, $url, ['class' => 'btn btn-default btn-sm', 'target' => '_blank']) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'value' => function($model){
                        return $model->nameList()[$model->name];
                    }
            ],
            'content',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', ['update-item', 'id' => $model->id]);
                        },
                    'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', ['delete-item', 'id' => $model->id]);
                        },
                ]
            ],
        ],
    ]); ?>

</div>
