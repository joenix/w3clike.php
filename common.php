<?php
/**
 +-----------------------------------------
 * Common
 * PHP 5.3+
 +-----------------------------------------
 * @category    Pt
 * @author      page7 <zhounan0120@gmail.com>
 * @version     $Id$
 +-----------------------------------------
 */

// define root path
define('PT_PATH', dirname(__FILE__).'/');

// local path to website path
define('WEB_PATH', '/');

// define core class / common path
define('CLASS_PATH', PT_PATH.'/class/');
define('COMMON_PATH', PT_PATH.'/common/');

// require common functions
include(COMMON_PATH.'func.php');

// register autoload
spl_autoload_register("__autoload");

// require convention config
config('web', include COMMON_PATH.'config.php');

// require custom config
if (defined("CONFIG"))
{
    config('web', include PT_PATH.CONFIG);
    define('CONFIG_PATH', dirname(PT_PATH.CONFIG).'/'); // defined project's config path.
}

if (DEBUG)
    error_reporting(E_ALL); // error report all
else
    error_reporting(0);

define('NOW', time()); // define a global timestamp

define('GZIP_ON', ini_get('output_handler') || ini_get('zlib.output_compression')); // check Gzip compression status

if (DEBUG)
    $GLOBALS['_startUseMems'] = memory_get_usage();

// determine whether the request is ajax
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    define('IS_AJAX', 1);
else if(!empty($_POST[config('web.ajax_var')]) || !empty($_GET[config('web.ajax_var')]))
    define('IS_AJAX', 1);
else
    define('IS_AJAX', 0);

// 处理系统异常
set_error_handler('error_handler');
set_exception_handler('throw_exception');

date_default_timezone_set(config('web.time_zone'));

if (defined('SESSION_ON'))
{
    $sessionName = ini_get('session.name');
    if ($_POST && isset($_POST[$sessionName]))
        session_id($_POST[$sessionName]);
    else if (isset($_GET[$sessionName]))
        session_id($_GET[$sessionName]);

    session_start();
}

header("Power-By: Pt-framework [zhounan0120@gmail.com]");


// need resflesh all browser catch
if (isset($_REQUEST[config('web.reflesh_var')]))
{
    header("Expires: Wed,01 Jan 2014 00:00:00 GMT"); // :p pt的开发日期
    header('Last-Modified:'.date('D,d M Y H:i:s').' GMT');
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
}

// language
if (config('web.i18n'))
    new language(config('lang'));

if (DEBUG)
    $GLOBALS['_initTime'] = microtime(TRUE);
    header("Access-Control-Allow-Origin: *");
?>