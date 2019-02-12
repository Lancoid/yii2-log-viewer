<?php

namespace lancoid\yii2LogViewer\controllers;

use lancoid\yii2LogViewer\{localization\LangHelper, models\Log, models\ZipLogForm, Module};
use yii\web\{Controller, NotFoundHttpException};
use yii\helpers\{ArrayHelper, Url};
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
                'class' => AccessControl::class,
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
     * @param      $slug
     * @param null $stamp
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($slug, $stamp = null)
    {
        $log = $this->find($slug, $stamp);

        return Yii::$app->response->sendFile(
            $log->fileName,
            basename($log->fileName),
            ['mimeType' => 'text/plain', 'inline' => true]
        );
    }

    /**
     * @param $slug
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionArchive($slug)
    {
        $log = $this->find($slug, null);
        $model = new ZipLogForm(['log' => $log]);
        if ($model->zip()) {
            Yii::$app->session->setFlash('success', LangHelper::langMessage($this->module->lang, 'archive_success'));

            return $this->redirect(['history', 'slug' => $slug]);
        }

        Yii::$app->session->setFlash('danger', LangHelper::langMessage($this->module->lang, 'archive_error'));

        return $this->refresh();
    }

    /**
     * @param      $slug
     * @param null $stamp
     * @param null $since
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($slug, $stamp = null, $since = null)
    {
        $log = $this->find($slug, $stamp);
        if ($since) {
            if ($log->updatedAt != $since) {
                Yii::$app->session->setFlash('error', 'delete error: file has updated');

                return $this->redirect(Url::previous());
            }
        }
        if (unlink($log->fileName)) {
            Yii::$app->session->setFlash('success', 'delete success');
        } else {
            Yii::$app->session->setFlash('error', 'delete error');
        }

        return $this->redirect(Url::previous());
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
     * @param string      $slug
     * @param null|string $stamp
     *
     * @return Log
     * @throws NotFoundHttpException
     */
    protected function find($slug, $stamp)
    {
        $log = $this->module->findLog($slug, $stamp);
        if (!$log) {
            throw new NotFoundHttpException(LangHelper::langMessage($this->module->lang, 'log_not_found'));
        }

        return $log;
    }
}
