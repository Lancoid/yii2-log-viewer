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
    public $sourcePath = '@bower/datatables-plugins/integration/bootstrap/3';

    /**
     * {@inheritdoc}
     */
    public $depends = [LogViewerBaseAsset::class];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->depends[] = 'yii\bootstrap\BootstrapAsset';
        $this->css[] = 'dataTables.bootstrap.css';
        $this->js[] = 'dataTables.bootstrap' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }
}
