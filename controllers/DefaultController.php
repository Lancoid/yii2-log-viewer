<?php

namespace lancoid\yii2LogViewer\controllers;

use lancoid\yii2LogViewer\{models\Log, models\ZipLogForm, Module};
use yii\web\{Controller, NotFoundHttpException};
use yii\helpers\{ArrayHelper, Url};
use yii\data\ArrayDataProvider;
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
     * @return string
     */
    public function actionIndex()
    {
        Url::remember();

        return $this->render('index', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $this->module->getLogs(),
                'sort' => [
                    'attributes' => [
                        'name',
                        'size' => ['default' => SORT_DESC],
                        'updatedAt' => ['default' => SORT_DESC],
                    ],
                ],
                'pagination' => ['pageSize' => 0],
            ]),
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
        if ($log->isExist) {
            return Yii::$app->response->sendFile($log->fileName, basename($log->fileName), [
                'mimeType' => 'text/plain',
                'inline' => true,
            ]);
        } else {
            throw new NotFoundHttpException('Log not found.');
        }
    }

    /**
     * @param $slug
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionArchive($slug)
    {
        if ($this->find($slug, null)->archive(date('YmdHis'))) {
            Yii::$app->session->setFlash('success', 'archive success');

            return $this->redirect(['history', 'slug' => $slug]);
        } else {
            throw new NotFoundHttpException('Log not found.');
        }
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
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $allLogs,
                'sort' => [
                    'attributes' => [
                        'fileName',
                        'size' => ['default' => SORT_DESC],
                        'updatedAt' => ['default' => SORT_DESC],
                    ],
                    'defaultOrder' => ['updatedAt' => SORT_DESC],
                ],
            ]),
            'fullSize' => $fullSize,
        ]);
    }

    /**
     * @param $slug
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionZip($slug)
    {
        $log = $this->find($slug, null);
        $model = new ZipLogForm(['log' => $log]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = $model->zip();
            if ($result !== false) {
                Yii::$app->session->setFlash('success', 'zip success');

                return $this->redirect(Url::previous());
            } else {
                Yii::$app->session->setFlash('error', 'zip error: ', implode('<br>', $model->getFirstErrors()));
            }
        }

        return $this->render('zip', [
            'model' => $model,
        ]);
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
        if ($log->isExist) {
            Yii::$app->response->sendFile($log->fileName)->send();
        } else {
            throw new NotFoundHttpException('Log not found.');
        }
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
        if ($log = $this->module->findLog($slug, $stamp)) {
            return $log;
        } else {
            throw new NotFoundHttpException('Log not found.');
        }
    }
}
