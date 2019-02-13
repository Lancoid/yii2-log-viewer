<?php

namespace lancoid\yii2LogViewer\models;

use yii\{web\ForbiddenHttpException, base\Model};

/**
 * Class DeleteLogForm
 *
 * @package lancoid\yii2LogViewer\models
 */
class DeleteLogForm extends Model
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
    public function delete()
    {
        try {
            if (!$this->module->canDelete) {
                throw new \Exception($this->module->messages['failureToDeleteSourceFile']);
            } else {
                if (!@unlink($this->log->fileName)) {
                    throw new \Exception($this->module->messages['failureToDeleteSourceFile']);
                }

                return true;
            }
        } catch (\Exception $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }
    }
}
