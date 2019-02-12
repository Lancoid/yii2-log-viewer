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

$formatter = new Formatter();
$fullSizeFormat = $formatter->format($fullSize, 'shortSize');
$zipBtn = $fullSize > 1 ? Html::a('zip', ['zip', 'slug' => Yii::$app->request->get('slug')], ['class' => 'btn btn-success btn-xs']) : '';
?>

<div class="log-viewer-history">
    <?= GridView::widget([
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'caption' => "full size: {$fullSizeFormat}. $zipBtn",
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
                'attribute' => 'counts',
                'format' => 'raw',
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
                'template' => '{view} {delete} {download}',
                'urlCreator' => function ($action, Log $log)
                {
                    return [$action, 'slug' => $log->slug, 'stamp' => $log->stamp];
                },
                'buttons' => [
                    'view' => function ($url, Log $log)
                    {
                        if ($log->isZip) {
                            return '';
                        }

                        return Html::a('View', $url, [
                            'class' => 'btn btn-xs btn-primary',
                            'target' => '_blank',
                        ]);
                    },
                    'delete' => function ($url)
                    {
                        return Html::a('Delete', $url, [
                            'class' => 'btn btn-xs btn-danger',
                            'data' => ['method' => 'post', 'confirm' => 'Are you sure?'],
                        ]);
                    },
                    'download' => function ($url, Log $log)
                    {
                        return !$log->isExist ? '' : Html::a('Download', $url, [
                            'class' => 'btn btn-xs btn-default',
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
</div>
