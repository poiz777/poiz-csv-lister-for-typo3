<?php

require_once __DIR__ . "/vendor/autoload.php";

defined("CODE_BASE")            or define("CODE_BASE",              __DIR__);
defined("CODE_POOL")            or define("CODE_POOL",              __DIR__);
defined("CODEPOOL")             or define("CODEPOOL",               __DIR__);
defined("SITE_ROOT")            or define("SITE_ROOT",              __DIR__ . "/../../../../");
defined("WEB_ROOT")             or define("WEB_ROOT",               SITE_ROOT);
defined("ABSPATH")              or define("ABSPATH",                SITE_ROOT);
defined("APP_ROOT")             or define("APP_ROOT",               SITE_ROOT);
defined("EXT_ROOT")             or define("EXT_ROOT",               __DIR__ . "/../../");
defined("EXT_PATH")             or define("EXT_PATH",               EXT_ROOT . "poiz_csv_lister/");
defined("EXT_URL")              or define("EXT_URL",                "/typo3conf/ext/poiz_csv_lister/");
defined("FILE_ADMIN")           or define("FILE_ADMIN",             SITE_ROOT . "fileadmin/");
defined("FILE_ADMIN_URL")       or define("FILE_ADMIN_URL",         "/fileadmin/");
defined("ENTITY_ROOT")          or define("ENTITY_ROOT",            __DIR__ . "/Pz/DataObjects");
defined("PLG_TEMP_STORE")       or define("PLG_TEMP_STORE",         FILE_ADMIN .  "poiz_csv_lister/");
defined("ICONS_BASE")           or define("ICONS_BASE",             EXT_PATH . "Resources/Public/Assets/images/icons/");
defined("APP_BASE_URL")         or define("APP_BASE_URL",           "");
defined("ICONS_URI")            or define("ICONS_URI",              EXT_URL . "Resources/Public/Assets/images/icons/");
defined("CSS_URI")              or define("CSS_URI",                EXT_URL . "Resources/Public/Assets/css/");
defined("JS_URI")               or define("JS_URI",                 EXT_URL . "Resources/Public/Assets/js/");
defined("AJAX_URI")             or define("AJAX_URI",               "/typo3conf/ext/poiz_csv_lister/CodePool/Pz/Ajax/Ajax.php");

// BRING-IN THE CONFIG OBJECT....
$confArray      = 	require __DIR__  . "/../../../LocalConfiguration.php";
$arrDB          = (is_array($confArray))    ? $confArray['DB']['Connections']['Default'] : null;
$charset        = isset($arrDB['charset'])  ? $arrDB['charset'] :'UTF-8';

//DATABASE CONNECTION CONFIGURATION:
defined("ADAPTER")          or define("ADAPTER",            "mysql");
defined("HOST")             or define("HOST",               $arrDB['host']);
defined("DBASE")            or define("DBASE",              $arrDB['dbname']);
defined("USER")             or define("USER",               $arrDB['user']);
defined("PASS")             or define("PASS",               $arrDB['password']);
defined("PORT")             or define("PORT",               $arrDB['port']);
defined("DRIVER")           or define("DRIVER",             $arrDB['driver']);
defined("CHARSET")          or define("CHARSET",            $charset);
defined("DB_FILE")          or define("DB_FILE",            WEB_ROOT . "dbs/pz_web_services");
defined("DSN_MYSQL")        or define("DSN_MYSQL",          'mysql:host=' . HOST . ';dbname=' . DBASE);
defined("DSN_SQLITE")       or define("DSN_SQLITE",         'sqlite:' . DB_FILE);

defined("TBL_PRX")          or define("TBL_PRX",            'tx_poizcsvlister_domain_model_');
defined("TBL_CSV_FILE")     or define("TBL_CSV_FILE",       TBL_PRX . 'pzcsvfile');
defined("TBL_CSV_LISTER")   or define("TBL_CSV_LISTER",     TBL_PRX . 'pzcsvlister');
