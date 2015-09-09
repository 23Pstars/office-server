<?php

/** untuk keperluan debugging */
error_reporting( E_ALL ^ E_DEPRECATED );

/**
 * set zona waktu untuk GMT+8
 */
date_default_timezone_set('Asia/Makassar');

require('define.db.php');
require('define.path.php');
require('define.info.php');
require('cls.autoload.php');
require('fn.helper.php');