<?php

namespace lancoid\yii2LogViewer\models;

use lancoid\yii2LogViewer\models\Log;
use yii\base\Model;
use ZipArchive;

/**
 * Class ZipLogForm
 *
 * @package lancoid\yii2LogViewer\models
 */
class ZipLogForm extends Model
{
    /**
     * @var Log
     */
    public $log;

    /**
     * @return bool
     */
    public function zip()
    {
        $log = $this->log;
        $current = date('Y_m_d.His');
        $fileName = Log::extractFileName($log->alias, "{$current}.zip");
        $zip = new ZipArchive();

        if ($zip->open($fileName, ZipArchive::CREATE) !== true) {
            $this->addError('log', 'cannot open zipFile, do you have permission?');

            return false;
        }

        $zip->addFile($log->fileName, basename($log->fileName));
        $zip->close();
        unlink($log->fileName);

        return true;
    }
}
