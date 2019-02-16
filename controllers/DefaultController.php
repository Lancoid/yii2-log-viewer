<?php

namespace lancoid\yii2LogViewer\controllers;

use lancoid\yii2LogViewer\models\ArchiveLogForm;
use lancoid\yii2LogViewer\models\DeleteLogForm;
use lancoid\yii2LogViewer\models\Log;
use lancoid\yii2LogViewer\models\ViewLogForm;
use lancoid\yii2LogViewer\Module;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use Yii;

/**
 * Class DefaultController
 *
 * @package lancoid\yii2LogViewer\controllers
 */
class DefaultController extends Controller
{
    /**
     * @var Module
     */
    public $module;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [['allow' => true, 'roles' => $this->module->accessRoles]],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        Url::remember();

        return $this->render(
            'index',
            [
                'dataProvider' => new ArrayDataProvider(
                    [
                        'allModels' => $this->module->getLogs(),
                        'pagination' => ['pageSize' => 0],
                    ]
                ),
            ]
        );
    }

    /**
     * @param $slug
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionHistory($slug)
    {
        Url::remember();
        $log = $this->find($slug, null);
        $allLogs = $this->module->getHistory($log);
        $fullSize = array_sum(ArrayHelper::getColumn($allLogs, 'size'));

        return $this->render('history', [
            'name' => $log->name,
            'dataProvider' => new ArrayDataProvider(
                [
                    'allModels' => $allLogs,
                    'sort' => [
                        'attributes' => ['updatedAt' => ['default' => SORT_DESC]],
                        'defaultOrder' => ['updatedAt' => SORT_DESC],
                    ],
                ]
            ),
            'fullSize' => $fullSize,
        ]);
    }

    /**
     * @param $slug
     *
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($slug)
    {
        $log = $this->find($slug, null);
        $model = new ViewLogForm(['log' => $log, 'module' => $this->module]);

        return $this->render('view', ['log' => $model->convertLogFile()]);
    }

    /**
     * @param $slug
     *
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionArchive($slug)
    {
        $log = $this->find($slug, null);
        $model = new ArchiveLogForm(['log' => $log, 'module' => $this->module]);

        if ($model->archive()) {
            Yii::$app->session->setFlash('success', $this->module->messages['archiveSuccess']);

            return $this->redirect(['history', 'slug' => $slug]);
        }

        return $this->refresh();
    }

    /**
     * @param $slug
     *
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($slug)
    {
        $log = $this->find($slug, null);
        $model = new DeleteLogForm(['log' => $log, 'module' => $this->module]);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', $this->module->messages['deleteSuccess']);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param      $slug
     * @param null $stamp
     *
     * @throws NotFoundHttpException
     */
    public function actionDownload($slug, $stamp = null)
    {
        $log = $this->find($slug, $stamp);
        Yii::$app->response->sendFile($log->fileName)->send();
    }

    /**
     * @param $slug
     * @param $stamp
     *
     * @return Log|null
     * @throws NotFoundHttpException
     */
    protected function find($slug, $stamp)
    {
        $log = $this->module->findLog($slug, $stamp);
        if (!$log) {
            throw new NotFoundHttpException($this->module->messages['logNotFound']);
        }

        return $log;
    }
}
