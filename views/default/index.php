<?php

use lancoid\yii2LogViewer\{models\Log, Module};
use yii\{grid\GridView, helpers\Html};

/* @var \yii\web\View $this */
/* @var \yii\data\ArrayDataProvider $dataProvider */

/* @var Module $module */
$module = $this->context->module;
$messages = $module->messages;

$this->title = $messages['logsTitle'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-viewer-index">
    <?= GridView::widget([
        'layout' => '{items}',
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'label' => $messages['nameInGrid'],
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
                'attribute' => 'size',
                'label' => $messages['sizeInGrid'],
                'format' => 'shortSize',
                'headerOptions' => ['class' => 'sort-ordinal'],
            ],
            [
                'attribute' => 'updatedAt',
                'label' => $messages['updatedAtInGrid'],
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
                    'history' => function ($url) use ($messages)
                    {
                        return Html::a(
                            '<i class="glyphicon glyphicon-folder-open"></i>',
                            $url,
                            ['class' => 'btn btn-xs btn-warning', 'title' => $messages['historyBtn']]
                        );
                    },
                    'view' => function ($url, Log $log) use ($messages)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-search"></i>',
                            $url,
                            ['class' => 'btn btn-xs btn-primary', 'title' => $messages['viewBtn']]
                        );
                    },
                    'archive' => function ($url, Log $log) use ($messages)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-compressed"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-info',
                                'title' => $messages['archiveBtn'],
                                'data' => ['method' => 'post', 'confirm' => $messages['sureAlert']],
                            ]
                        );
                    },
                    'delete' => function ($url, Log $log) use ($module, $messages)
                    {
                        return ($module->canDelete && $log->isExist) ? Html::a(
                            '<i class="glyphicon glyphicon-remove"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger',
                                'title' => $messages['deleteBtn'],
                                'data' => ['method' => 'post', 'data-a' => 'aa', 'confirm' => $messages['sureAlert']],
                            ]
                        ) : '';
                    },
                    'download' => function ($url, Log $log) use ($messages)
                    {
                        return !$log->isExist ? '' : Html::a(
                            '<i class="glyphicon glyphicon-download-alt"></i>',
                            $url,
                            ['class' => 'btn btn-xs btn-success', 'title' => $messages['downloadBtn']]
                        );
                    },
                ],
            ],
        ],
    ]) ?>
</div>
