<?php

/** init semua kebutuhan konfigurasi */
require_once( 'includes/init.php' );

/**
 * parse semua parameter yang diberikan,
 * @reference Routes.php
 */
LRS\OfficeServer\Controller\Routes::get_instance()
    ->parse_page( $_REQUEST );