<?php

namespace lancoid\yii2LogViewer\models;

use lancoid\yii2LogViewer\models\Log;
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
     * @var Log
     */
    public $log;

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
                throw new \Exception('cannot open zipFile, do you have permission?');
            }

            if (!$zip->addFile($log->fileName, basename($log->fileName))) {
                throw new \Exception('Failure to add file, do you have permission?');
            }

            if (!$zip->close()) {
                throw new \Exception('Failure to create temporary file, do you have permission?');
            }

            if (!@unlink($log->fileName)) {
                throw new \Exception('Failure to delete source file, do you have permission?');
            }
        } catch (\Exception $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }

        return true;
    }
}
