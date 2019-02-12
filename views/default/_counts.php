<?php

use yii\helpers\Html;

/* @var \yii\web\View $this */
/* @var \lancoid\yii2LogViewer\models\Log $log */

/* @var \lancoid\yii2LogViewer\Module $module */
$module = $this->context->module;

foreach ($log->getCounts() as $level => $count) {
    $class = isset($module->levelClasses[$level]) ? $module->levelClasses[$level] : $module->defaultLevelClass;
    echo Html::tag('span', $count, ['class' => 'label ' . $class, 'title' => $level]) . ' ';
}
