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
     * @var
     */
    public $start;

    /**
     * @var
     */
    public $end;

    /**
     * @var int
     */
    public $deleteAfterZip = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start', 'end'], 'string'],
            [['deleteAfterZip'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'start' => 'Start Date',
            'end' => 'End Date',
            'deleteAfterZip' => 'Is Delete After Zip',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->start = date('Y-m-01');
        $this->end = date('Y-m-d');
    }

    /**
     * @return bool
     */
    public function zip()
    {
        $log = $this->log;
        $startStamp = date('Ymd', strtotime($this->start));
        $endStamp = date('Ymd', strtotime($this->end));
        $logs = [];
        foreach (glob(Log::extractFileName($log->alias, '*')) as $fileName) {
            $logEnd = Log::extractFileStamp($log->alias, $fileName);
            $arr = explode('.', $logEnd);
            if ($arr) {
                $logEnd = $arr[0];
            }
            $stamp = date('Ymd', strtotime($logEnd));
            if ($stamp >= $startStamp && $stamp <= $endStamp) {
                $log = new Log($log->name, $log->alias, Log::extractFileStamp($log->alias, $fileName));
                if (!$log->isZip) {
                    $logs[] = $log;
                }
            }
        }
        $current = date('YmdHis');
        $fileName = Log::extractFileName($log->alias, "{$startStamp}-{$endStamp}-{$current}.zip");
        $zip = new ZipArchive();
        if ($zip->open($fileName, ZipArchive::CREATE) !== true) {
            $this->addError('log', 'cannot open zipFile, do you have permission?');

            return false;
        }
        foreach ($logs as $log) {
            $zip->addFile($log->fileName, basename($log->fileName));
        }
        $zip->close();

        if ($this->deleteAfterZip) {
            foreach ($logs as $log) {
                unlink($log->fileName);
            }
        }

        return true;
    }
}
