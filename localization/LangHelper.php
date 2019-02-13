<?php

namespace lancoid\yii2LogViewer\localization;

/**
 * Class LangHelper
 *
 * @package lancoid\yii2LogViewer\localization
 */
class LangHelper
{
    /**
     * @var array
     */
    private static $acceptedLanguage = ['en', 'ru'];

    /**
     * @var array
     */
    private static $messages = [
        'en' => [
            // module
            'webApplicationOnly' => 'Can use for web application only.',

            // controller
            'logNotFound' => 'Log not found.',
            'archiveSuccess' => 'Archive Success',
            'deleteSuccess' => 'Delete success',

            // models
            'cannotOpenZipFile' => 'Cannot open zipFile, do you have permission?',
            'failureToAddZipFile' => 'Failure to add zipFile, do you have permission?',
            'failureToCreateTemporaryFile' => 'Failure to create temporary file, do you have permission?',
            'failureToDeleteSourceFile' => 'Failure to delete source file, do you have permission?',

            // views
            'logTitle' => 'Log',
            'logsTitle' => 'Logs',
            'nameInGrid' => 'Name',
            'sizeInGrid' => 'Size',
            'updatedAtInGrid' => 'UpdatedAt',
            'fullSize' => 'Full size',
            'fileNameInGrid' => 'Filename',

            // buttons
            'historyBtn' => 'History',
            'viewBtn' => 'View',
            'archiveBtn' => 'Archive',
            'deleteBtn' => 'Delete',
            'downloadBtn' => 'Download',

            // alert
            'sureAlert' => 'Are you sure?',
        ],
        'ru' => [
            // module
            'webApplicationOnly' => 'Использование возможно только в веб-приложениях.',

            // controller
            'logNotFound' => 'Лог не найден.',
            'archiveSuccess' => 'Архивация проведена успешно',
            'deleteSuccess' => 'Удаление проведено успешно',

            // models
            'cannotOpenZipFile' => 'Cannot open zipFile.',
            'failureToAddZipFile' => 'Failure to add zipFile.',
            'failureToCreateTemporaryFile' => 'Failure to create temporary file.',
            'failureToDeleteSourceFile' => 'Failure to delete source file.',

            // views
            'logTitle' => 'Лог',
            'logsTitle' => 'Логи',
            'nameInGrid' => 'Имя',
            'sizeInGrid' => 'Размер',
            'updatedAtInGrid' => 'Последние изменения',
            'fullSize' => 'Полный размер',
            'fileNameInGrid' => 'Имя файла',

            // buttons
            'historyBtn' => 'История',
            'viewBtn' => 'Просмотр',
            'archiveBtn' => 'Архивировать',
            'deleteBtn' => 'Удалить',
            'downloadBtn' => 'Скачать',

            // alert
            'sureAlert' => 'Вы уверены в этом действии?',
        ],
    ];

    /**
     * @param string $lang
     *
     * @return array
     */
    public static function setMessageArray(string $lang)
    {
        $key = in_array($lang, self::$acceptedLanguage) ? $lang : 'en';

        return self::$messages[$key];
    }
}
