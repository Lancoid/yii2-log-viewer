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
        // controller
        'en' => [
            'log_not_found' => 'Log not found.',
            'archive_success' => 'Archive Success',
            'archive_error' => 'Archive Error',
        ],
        'ru',
    ];

    /**
     * @param string $lang
     * @param string $message
     *
     * @return mixed
     */
    public static function langMessage(string $lang, string $message)
    {
        $language = key_exists($lang, self::$acceptedLanguage) ? $lang : 'en';

        return self::$messages[$language][$message];
    }
}
