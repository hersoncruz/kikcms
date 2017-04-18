<?php

namespace KikCMS\Config;


class KikCMSConfig
{
    const ENV_DEV  = 'dev';
    const ENV_PROD = 'prod';

    const PACKAGE_NAME = 'kiksaus/kikcms';

    const CONTENT_TYPES = [
        'text'         => self::CONTENT_TYPE_TEXT,
        'textarea'     => self::CONTENT_TYPE_TEXTAREA,
        'int'          => self::CONTENT_TYPE_INT,
        'checkbox'     => self::CONTENT_TYPE_CHECKBOX,
        'tinymce'      => self::CONTENT_TYPE_TINYMCE,
        'image'        => self::CONTENT_TYPE_IMAGE,
        'file'         => self::CONTENT_TYPE_FILE,
        'tab'          => self::CONTENT_TYPE_TAB,
        'pagepicker'   => self::CONTENT_TYPE_PAGEPICKER,
        'date'         => self::CONTENT_TYPE_DATE,
        'datetime'     => self::CONTENT_TYPE_DATETIME,
        'time'         => self::CONTENT_TYPE_TIME,
        'select'       => self::CONTENT_TYPE_SELECT,
        'select_table' => self::CONTENT_TYPE_SELECT_TABLE,
        'radio'        => self::CONTENT_TYPE_RADIO,
        'color'        => self::CONTENT_TYPE_COLOR,
        'custom'       => self::CONTENT_TYPE_CUSTOM,
    ];

    const CONTENT_TYPE_TEXT         = 1;
    const CONTENT_TYPE_TEXTAREA     = 2;
    const CONTENT_TYPE_INT          = 3;
    const CONTENT_TYPE_CHECKBOX     = 4;
    const CONTENT_TYPE_TINYMCE      = 5;
    const CONTENT_TYPE_IMAGE        = 6;
    const CONTENT_TYPE_FILE         = 7;
    const CONTENT_TYPE_TAB          = 8;
    const CONTENT_TYPE_PAGEPICKER   = 9;
    const CONTENT_TYPE_DATE         = 10;
    const CONTENT_TYPE_DATETIME     = 11;
    const CONTENT_TYPE_TIME         = 12;
    const CONTENT_TYPE_SELECT       = 13;
    const CONTENT_TYPE_SELECT_TABLE = 14;
    const CONTENT_TYPE_RADIO        = 15;
    const CONTENT_TYPE_COLOR        = 16;
    const CONTENT_TYPE_CUSTOM       = 17;
}