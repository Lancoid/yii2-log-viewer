<?php

namespace lancoid\yii2LogViewer;

use lancoid\yii2LogViewer\localization\LangHelper;
use lancoid\yii2LogViewer\models\Log;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\web\Application;

/**
 * Class Module
 *
 * @package lancoid\yii2LogViewer
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var array
     */
    public $aliases = [];

    /**
     * @var string
     */
    public $lang = 'en';

    /**
     * @var string
     */
    public $accessRoles = [];

    /**
     * @var string
     */
    public $moduleUrl;

    /**
     * @var string
     */
    public $canDelete = false;

    /**
     * @var string
     */
    public $appLayout = null;

    /**
     * @var array
     */
    public $levelClasses = [
        'trace' => 'label-default',
        'info' => 'label-info',
        'warning' => 'label-warning',
        'error' => 'label-danger',
    ];

    /**
     * @var string
     */
    public $defaultLevelClass = 'label-default';

    /**
     * @var array
     */
    public $messages = [];

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $this->messages = LangHelper::setMessageArray($this->lang);

        if (!$app instanceof Application) {
            throw new InvalidConfigException($this->messages['webApplicationOnly']);
        }

        if ($this->appLayout) {
            $this->layout = $this->appLayout;
        }

        $url = $this->moduleUrl ? $this->moduleUrl : $this->id;

        $app->getUrlManager()->addRules(
            [
                $url => $this->id . '/default/index',
                $url . '/<action:\w+>/<slug:[\w-]+>' => $this->id . '/default/<action>',
                $url . '/<action:\w+>' => $this->id . '/default/<action>',
            ],
            false
        );
    }

    /**
     * @return Log[]
     */
    public function getLogs()
    {
        $logs = [];
        foreach ($this->aliases as $name => $alias) {
            $logs[] = new Log($name, $alias);
        }

        return $logs;
    }

    /**
     * @param string      $slug
     * @param null|string $stamp
     *
     * @return null|Log
     */
    public function findLog($slug, $stamp)
    {
        foreach ($this->aliases as $name => $alias) {
            if ($slug === Log::extractSlug($name)) {
                return new Log($name, $alias, $stamp);
            }
        }

        return null;
    }

    /**
     * @param Log $log
     *
     * @return Log[]
     */
    public function getHistory(Log $log)
    {
        $logs = [];
        foreach (glob(Log::extractFileName($log->alias, '*')) as $fileName) {
            $logs[] = new Log($log->name, $log->alias, Log::extractFileStamp($log->alias, $fileName));
        }

        return $logs;
    }
}
