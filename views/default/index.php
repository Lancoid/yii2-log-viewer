<?php

use lancoid\yii2LogViewer\models\Log;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var \yii\web\View $this */
/* @var \yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = 'Logs';
?>
<div class="log-viewer-index">
    <?= GridView::widget([
        'layout' => '{items}',
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Log $log)
                {
                    return Html::tag('h5', join("\n", [
                        Html::encode($log->name),
                        '<br/>',
                        Html::tag('small', Html::encode($log->fileName)),
                    ]));
                },
            ],
            [
                'attribute' => 'counts',
                'format' => 'raw',
                'headerOptions' => ['class' => 'sort-ordinal'],
                'value' => function (Log $log)
                {
                    return $this->render('_counts', ['log' => $log]);
                },
            ],
            [
                'attribute' => 'size',
                'format' => 'shortSize',
                'headerOptions' => ['class' => 'sort-ordinal'],
            ],
            [
                'attribute' => 'updatedAt',
                'format' => 'relativeTime',
                'headerOptions' => ['class' => 'sort-numerical'],
            ],
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{history} {view} {archive} {delete} {download}',
                'urlCreator' => function ($action, Log $log)
                {
                    return [$action, 'slug' => $log->slug];
                },
                'buttons' => [
                    'history' => function ($url)
                    {
                        return Html::a(
                            '<i class="glyphicon glyphicon-folder-open"></i>',
                            $url,
                            ['class' => 'btn btn-xs btn-warning', 'title' => 'history']
                        );
                    },
                    'view' => function ($url, Log $log)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-search"></i>',
                            $url,
                            ['class' => 'btn btn-xs btn-primary', 'title' => 'view']
                        );
                    },
                    'archive' => function ($url, Log $log)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-compressed"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-info',
                                'title' => 'archive',
                                'data' => ['method' => 'post', 'confirm' => 'Are you sure?'],
                            ]
                        );
                    },
                    'delete' => function ($url, Log $log)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-remove"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger',
                                'title' => 'delete',
                                'data' => ['method' => 'post', 'data-a' => 'aa', 'confirm' => 'Are you sure?'],
                            ]
                        );
                    },
                    'download' => function ($url, Log $log)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-download-alt"></i>',
                            $url,
                            ['class' => 'btn btn-xs btn-success', 'title' => 'download']
                        );
                    },
                ],
            ],
        ],
    ]) ?>
</div>
