<?php

require_once('./manifest.php');


/* -----︾----- Site Start -----︾----- */


// Console Server
// console( $_SERVER );


// Datebase Object
$db = db( config('db') );

// Define Host
define('HOST', empty( $_SERVER['HTTP_HOST'] ) ? 'http://www.w3clike.cn:8888/' : ( 'http://' . $_SERVER['HTTP_HOST'] . '/' ) );

// Request Uri
$uri = parse_url( $_SERVER['REQUEST_URI'] );

// Suffix
$suffix = explode(' ', SUFFIX);

// Module
$module = convert_path( $uri['path'] );

// Param
$param = empty( $uri['query'] ) ? '' : convert_query( $uri['query'] );

// Active
$active = $module[0];

if( !$active ){
	$active = 'index';
}


// Module
if( in_array( $active, explode(' ', MODULE) ) )
{
	include('./module/' . $active . '.php');
}
else{
	include('./module/404.php');
}


// Init
template::assign( 'active', $active );
template::display( $active, true );


/* -----︽----- Site End -----︽----- */