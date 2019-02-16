<?php

namespace lancoid\yii2LogViewer\models;

use yii\web\ForbiddenHttpException;
use yii\base\Model;
use ZipArchive;

/**
 * Class ArchiveLogForm
 *
 * @package lancoid\yii2LogViewer\models
 */
class ArchiveLogForm extends Model
{
    /**
     * @var \lancoid\yii2LogViewer\models\Log
     */
    public $log;

    /**
     * @var \lancoid\yii2LogViewer\Module
     */
    public $module;

    /**
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function archive()
    {
        $log = $this->log;
        $current = date('Y_m_d.His');
        $fileName = Log::extractFileName($log->alias, "{$current}.zip");
        $zip = new ZipArchive();

        try {
            if (!$zip->open($fileName, ZipArchive::CREATE)) {
                throw new \Exception($this->module->messages['cannotOpenZipFile']);
            }

            if (!$zip->addFile($log->fileName, basename($log->fileName))) {
                throw new \Exception($this->module->messages['failureToAddZipFile']);
            }

            if (!$zip->close()) {
                throw new \Exception($this->module->messages['failureToCreateTemporaryFile']);
            }

            if (!@unlink($log->fileName)) {
                throw new \Exception($this->module->messages['failureToDeleteSourceFile']);
            }
        } catch (\Exception $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }

        return true;
    }
}
