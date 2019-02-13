<?php

namespace lancoid\yii2LogViewer\models;

use lancoid\yii2LogViewer\models\Log;
use yii\web\ForbiddenHttpException;
use yii\base\Model;

/**
 * Class DeleteLogForm
 *
 * @package lancoid\yii2LogViewer\models
 */
class DeleteLogForm extends Model
{
    /**
     * @var Log
     */
    public $log;

    /**
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function delete()
    {
        try {
            if (!@unlink($this->log->fileName)) {
                throw new \Exception('Failure to delete source file, do you have permission?');
            }

            return true;
        } catch (\Exception $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }
    }
}
