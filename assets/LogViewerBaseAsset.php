<?php

namespace lancoid\yii2LogViewer\assets;

use yii\web\AssetBundle;

/**
 * Class LogViewerBaseAsset
 *
 * @package lancoid\yii2LogViewer\assets
 */
class LogViewerBaseAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@bower/datatables/media';

    /**
     * {@inheritdoc}
     */
    public $depends = ['yii\web\JqueryAsset'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->js[] = 'js/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $this->css[] = 'css/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.css';
    }
}
