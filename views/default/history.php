<?php

use lancoid\yii2LogViewer\models\Log;
use yii\i18n\Formatter;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var \yii\web\View $this */
/* @var \yii\data\ArrayDataProvider $dataProvider */
/* @var string $name */
/* @var integer $fullSize */

$this->title = $name;
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $name;

$fullSizeFormat = (new Formatter())->format($fullSize, 'shortSize');
?>

<div class="log-viewer-history">
    <?= GridView::widget([
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'caption' => "full size: {$fullSizeFormat}",
        'columns' => [
            [
                'attribute' => 'fileName',
                'format' => 'raw',
                'value' => function (Log $log)
                {
                    return pathinfo($log->fileName, PATHINFO_BASENAME);
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
                'template' => '{download}',
                'urlCreator' => function ($action, Log $log)
                {
                    return [$action, 'slug' => $log->slug, 'stamp' => $log->stamp];
                },
                'buttons' => [
//                    'delete' => function ($url, Log $log)
//                    {
//                        return Html::a(
//                            '<i class="glyphicon glyphicon-remove"></i>',
//                            $url,
//                            [
//                                'class' => 'btn btn-xs btn-danger',
//                                'title' => 'delete',
//                                'data' => ['method' => 'post', 'confirm' => 'Are you sure?'],
//                            ]
//                        );
//                    },
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
