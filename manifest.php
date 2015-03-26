<?php

// session start
define('SESSION_ON', true);

// define framework config
define('CONFIG', '/conf/web.php');

// debug switch
define('DEBUG', false);

// suffix be allowed
define('SUFFIX', 'jpg jpeg png gif bmp eot svg ttf woff woff2 css less js json');

// module be allowed
define('MODULE', 'index search star about');

// include common
include('./common.php');

// include project common functions
include(COMMON_PATH.'web_func.php');

// console
function console( $object, $mode = null ){
	echo '<pre>';
	empty($mode) ? print_r( $object ) : var_dump( $object );
	exit;
}
