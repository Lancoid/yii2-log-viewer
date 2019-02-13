<?php

use yii\{i18n\Formatter, grid\GridView, helpers\Html};
use lancoid\yii2LogViewer\{models\Log, Module};

/* @var \yii\web\View $this */
/* @var \yii\data\ArrayDataProvider $dataProvider */
/* @var string $name */
/* @var integer $fullSize */

/* @var Module $module */
$module = $this->context->module;
$messages = $module->messages;

$this->title = $messages['logTitle'] . ': ' . $name;
$this->params['breadcrumbs'][] = ['label' => $messages['logsTitle'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $name;

$fullSizeFormat = (new Formatter())->format($fullSize, 'shortSize');
?>

<div class="log-viewer-history">
    <?= GridView::widget([
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'caption' => $messages['fullSize'] . ": {$fullSizeFormat}",
        'columns' => [
            [
                'attribute' => 'fileName',
                'label' => $messages['fileNameInGrid'],
                'format' => 'raw',
                'value' => function (Log $log)
                {
                    return pathinfo($log->fileName, PATHINFO_BASENAME);
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
                'template' => '{delete} {download}',
                'urlCreator' => function ($action, Log $log)
                {
                    return [$action, 'slug' => $log->slug, 'stamp' => $log->stamp];
                },
                'buttons' => [
                    'delete' => function ($url) use ($module, $messages)
                    {
                        return $module->canDelete ? Html::a(
                            '<i class="glyphicon glyphicon-remove"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger',
                                'title' => $messages['deleteBtn'],
                                'data' => ['method' => 'post', 'confirm' => $messages['sureAlert']],
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
