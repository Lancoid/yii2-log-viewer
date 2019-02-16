<?php

namespace lancoid\yii2LogViewer\models;

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
            if ($this->module->canDelete) {
                if (!@unlink($this->log->fileName)) {
                    throw new \Exception($this->module->messages['failureToDeleteSourceFile']);
                }

                return true;
            } else {
                throw new \Exception($this->module->messages['failureCanDeletePermission']);
            }
        } catch (\Exception $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }
    }
}
