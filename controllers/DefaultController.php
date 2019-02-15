<?php

namespace lancoid\yii2LogViewer\controllers;

use lancoid\yii2LogViewer\{models\ArchiveLogForm, models\DeleteLogForm, models\Log, Module};
use yii\web\{Controller, ForbiddenHttpException, NotFoundHttpException};
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

        $key = null;
        $array = [];
        if ($file = fopen($log->getFileName(), 'r')) {
            while (($line = fgets($file)) !== false) {
                if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $line, $date)) {
                    $key = $key === null ? 0 : $key + 1;
                    if (!strpos($line, '$_GET')) {
                        $preparedLine = rtrim($line);
                    } else {
                        $preparedLine = str_replace(' $_GET = [', '', rtrim($line));
                        $array[$key]['details'][] = '$_GET = [';
                    }

                    $aaa = explode('][', str_replace([$date[0] . ' '], '', $preparedLine));

                    $array[$key]['date'] = $date[0];
                    $array[$key]['ip'] = str_replace('[', '', $aaa[0]);
                    $array[$key]['error-type'] = $aaa[3];
                    $array[$key]['error'] = str_replace(']', '\r\n', $aaa[4]);
                } else {
                    if (strlen($line) !== 1) {
                        $details = str_replace(["<address>", "</address>", "\r", "\n", "\r\n"], '', rtrim($line));
                        if ($details !== '\'') {
                            $array[$key]['details'][] = $details;
                        }
                    }
                }
            }
        }

        return $this->render('view', ['logs' => array_reverse($array)]);
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
        $model = new ArchiveLogForm(['log' => $log]);

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
        $model = new DeleteLogForm(['log' => $log]);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', $this->module->messages['deleteSuccess']);
        }

        return $this->refresh();
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
