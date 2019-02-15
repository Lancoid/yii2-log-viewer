<?php

namespace lancoid\yii2LogViewer\assets;

use yii\web\AssetBundle;

/**
 * Class LogViewerAsset
 *
 * @package lancoid\yii2LogViewer\assets
 */
class LogViewerAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@bower/datatables/media';

    /**
     * {@inheritdoc}
     */
    public $depends = ['yii\web\JqueryAsset', 'yii\bootstrap\BootstrapAsset'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->js[] = 'js/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $this->js[] = 'js/dataTables.bootstrap' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $this->css[] = 'css/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.css';
        $this->css[] = 'css/dataTables.bootstrap' . (YII_ENV_DEV ? '' : '.min') . '.css';
    }
}
