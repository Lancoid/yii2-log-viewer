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
            'cannotOpenZipFile' => 'Cannot open zipFile.',
            'cannotOpenLogFile' => 'Cannot open logFile.',
            'failureToAddZipFile' => 'Failure to add zipFile.',
            'failureToCreateTemporaryFile' => 'Failure to create temporary file.',
            'failureToDeleteSourceFile' => 'Failure to delete source file.',
            'failureCanDeletePermission' => 'Action not allowed by settings.',

            // views
            'logTitle' => 'Log',
            'logsTitle' => 'Logs',
            'nameInGrid' => 'Name',
            'sizeInGrid' => 'Size',
            'updatedAtInGrid' => 'UpdatedAt',
            'fullSize' => 'Full size',
            'fileNameInGrid' => 'Filename',
            'viewLogType' => 'Type & address',
            'viewLogDetails' => 'Details',

            // buttons
            'historyBtn' => 'History',
            'viewBtn' => 'View',
            'archiveBtn' => 'Archive',
            'deleteBtn' => 'Delete',
            'downloadBtn' => 'Download',

            // alert
            'sureAlert' => 'Are you sure?',

            // datatables script
            'datatablesProcessing' => 'Processing...',
            'datatablesSearch' => 'Search:',
            'datatablesLengthMenu' => 'Show _MENU_ entries',
            'datatablesInfo' => 'Showing _START_ to _END_ of _TOTAL_ entries.',
            'datatablesInfoEmpty' => 'Showing 0 to 0 of 0 entries.',
            'datatablesInfoFiltered' => '(filtered from _MAX_ total entries)',
            'datatablesInfoPostFix' => '',
            'datatablesLoadingRecords' => 'Loading...',
            'datatablesZeroRecords' => 'No matching records found.',
            'datatablesEmptyTable' => 'No data available in table.',
            'datatablesFirstPage' => 'First',
            'datatablesPreviousPage' => 'Previous',
            'datatablesNextPage' => 'Next',
            'datatablesLastPage' => 'Last',
            'datatablesSortAscending' => ': activate to sort column ascending',
            'datatablesSortDescending' => ': activate to sort column descending',

        ],
        'ru' => [
            // module
            'webApplicationOnly' => 'Использование возможно только в веб-приложениях.',

            // controller
            'logNotFound' => 'Лог не найден.',
            'archiveSuccess' => 'Архивация проведена успешно',
            'deleteSuccess' => 'Удаление проведено успешно',

            // models
            'cannotOpenZipFile' => 'Невозможно открыть zip-файл.',
            'cannotOpenLogFile' => 'Невозможно открыть log-файл.',
            'failureToAddZipFile' => 'Ошибка добавления zip-файла.',
            'failureToCreateTemporaryFile' => 'Ошибка создания временного файла.',
            'failureToDeleteSourceFile' => 'Ошибка удаления источника.',
            'failureCanDeletePermission' => 'Действие не разрешено настройками',

            // views
            'logTitle' => 'Лог',
            'logsTitle' => 'Логи',
            'nameInGrid' => 'Имя',
            'sizeInGrid' => 'Размер',
            'updatedAtInGrid' => 'Последние изменения',
            'fullSize' => 'Полный размер',
            'fileNameInGrid' => 'Имя файла',
            'viewLogType' => 'Тип и адрес',
            'viewLogDetails' => 'Подробности',

            // buttons
            'historyBtn' => 'История',
            'viewBtn' => 'Просмотр',
            'archiveBtn' => 'Архивировать',
            'deleteBtn' => 'Удалить',
            'downloadBtn' => 'Скачать',

            // alert
            'sureAlert' => 'Вы уверены в этом действии?',

            // datatables
            'datatablesProcessing' => 'Подождите...',
            'datatablesSearch' => 'Поиск:',
            'datatablesLengthMenu' => 'Показать _MENU_ записей',
            'datatablesInfo' => 'Записи с _START_ до _END_ из _TOTAL_ записей.',
            'datatablesInfoEmpty' => 'Записи с 0 до 0 из 0 записей.',
            'datatablesInfoFiltered' => '(отфильтровано из _MAX_ записей)',
            'datatablesInfoPostFix' => '',
            'datatablesLoadingRecords' => 'Загрузка записей...',
            'datatablesZeroRecords' => 'Записи отсутствуют.',
            'datatablesEmptyTable' => 'В таблице отсутствуют данные.',
            'datatablesFirstPage' => 'Первая',
            'datatablesPreviousPage' => 'Предыдущая',
            'datatablesNextPage' => 'Следующая',
            'datatablesLastPage' => 'Последняя',
            'datatablesSortAscending' => ': активировать для сортировки столбца по возрастанию',
            'datatablesSortDescending' => ': активировать для сортировки столбца по убыванию',
        ],
    ];

    /**
     * @param $lang
     *
     * @return mixed
     */
    public static function setMessageArray($lang)
    {
        $key = in_array($lang, self::$acceptedLanguage) ? $lang : 'en';

        return self::$messages[$key];
    }
}
