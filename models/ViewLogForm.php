<?php

namespace lancoid\yii2LogViewer\models;

use yii\{web\ForbiddenHttpException, base\Model};

/**
 * Class ViewLogForm
 *
 * @package lancoid\yii2LogViewer\models
 */
class ViewLogForm extends Model
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
    public function convertLogFile()
    {
        $log = $this->log;
        $key = null;
        $array = [];

        try {
            $file = fopen($log->getFileName(), 'r');
            if (!$file) {
                throw new \Exception($this->module->messages['cannotOpenLogFile']);
            }

            while (($line = fgets($file)) !== false) {
                if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $line, $date)) {
                    $key = $key === null ? 0 : $key + 1;
                    if (!strpos($line, '$_GET')) {
                        $preparedLine = rtrim($line);
                    } else {
                        if (strpos($line, ' $_GET = []')) {
                            $preparedLine = str_replace(' $_GET = []', '', rtrim($line));
                            $array[$key]['details'][] = '$_GET = []';
                        } else {
                            $preparedLine = str_replace(' $_GET = [', '', rtrim($line));
                            $array[$key]['details'][] = '$_GET = [';
                        }

                    }

                    $aaa = explode('][', str_replace([$date[0] . ' '], '', $preparedLine));

                    $array[$key]['date'] = $date[0];
                    $array[$key]['ip'] = str_replace('[', '', $aaa[0]);
                    $array[$key]['error-type'] = $aaa[3];
                    $array[$key]['error-icon'] = $this->setErrorIcon($aaa[3]);
                    $array[$key]['error-color'] = $this->setErrorColor($aaa[3]);

                    $array[$key]['error'] = str_replace(']', '<br>', $aaa[4]);
                } else {
                    if (strlen($line) !== 1) {
                        $details = str_replace(["<address>", "</address>", "\r", "\n", "\r\n"], '', rtrim($line));
                        if ($details !== '\'') {
                            $array[$key]['details'][] = $details;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }

        return array_reverse($array);
    }

    private function setErrorIcon($type)
    {
        switch ($type) {
            case 'error':
                return 'fire';
                break;
            case 'warning':
                return 'alert';
                break;
            case 'info':
                return 'pushpin';
                break;
        }
    }

    private function setErrorColor($type)
    {
        switch ($type) {
            case 'error':
                return 'text-danger';
                break;
            case 'warning':
                return 'text-warning';
                break;
            case 'info':
                return 'text-info';
                break;
        }
    }
}
