<?php
/**
 * daftarkan master autoload untuk controllers dan models
 * demi mempermudah instance
 */

spl_autoload_register( function( $class ){

    $class_to_include = '';

    /**
     * sejak menggunakan namespace, ini menjadi perlu
     * karena format parameter $class akan berbeda.
     * maka perlu untuk di parsing / filter terlebih dahulu
     */
    $parts = explode( '\\', $class );
    list( ,, $type ) = $parts;
    $class_name = array_pop( $parts );

    /** cek apakah class adalah controller */
    if( 'Controller' == $type )
        $class_to_include = is_readable( $x = LRS_CONTROLLER_ABS_PATH . DS . $class_name . '.php' ) ? $x : '';

    /** cek juga apakah class adalah model */
    else if ( 'Model' == $type )
        $class_to_include = is_readable( $x = LRS_MODEL_ABS_PATH . DS . $class_name . '.php' ) ? $x : '';

    /** jika kosong, gak usah di proses */
    if( !empty( $class_to_include ) ) include( $class_to_include );

} );