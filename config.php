<?php
const _MODULE_DEFAULT = 'home';
const _ACTION_DEFAULT = 'lists';

const _INCODE = true;

// Thiết lập host
define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/users-manager');
define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT.'/templates');

// Thiết lập path
define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH_ROOT.'/templates');

// Thiết lập kết nối DB
const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'php_online';
const _DRIVER = 'mysql';