<?php

$uri_path = 'http://127.0.0.1/lrsoft/office-server';


/** =================
 *   JANGAN DIGANTI
 * ================== */

/** pemisah direktory */
define( 'DS', DIRECTORY_SEPARATOR );
/** absolute path */
define( 'LRS_ABS_PATH', dirname( dirname( __FILE__ ) ) );
/** uri path */
define( 'LRS_URI_PATH', $uri_path );
/** absolute path untuk include */
define( 'LRS_INCLUDE_ABS_PATH', LRS_ABS_PATH . DS . 'includes' );
/** absolute path untuk controller */
define( 'LRS_CONTROLLER_ABS_PATH', LRS_ABS_PATH . DS . 'controllers' );
/** absolute path untuk model */
define( 'LRS_MODEL_ABS_PATH', LRS_ABS_PATH . DS . 'models' );
/** absolute path untuk view */
define( 'LRS_VIEW_ABS_PATH', LRS_ABS_PATH . DS . 'views' );
/** uri path untuk asset */
define( 'LRS_ASSET_URI_PATH', LRS_URI_PATH . DS . 'assets' );
/** uri path untuk style */
define( 'LRS_STYLE_URI_PATH', LRS_ASSET_URI_PATH . DS . 'styles' );
/** uri path untuk script */
define( 'LRS_SCRIPT_URI_PATH', LRS_ASSET_URI_PATH . DS . 'scripts' );
/** uri path untuk image */
define( 'LRS_IMAGE_URI_PATH', LRS_ASSET_URI_PATH . DS . 'images' );