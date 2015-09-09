<?php

/**
 * Class ini berisi fungsi untuk keperluan
 * operasi komponen halaman
 */

namespace LRS\OfficeServer\Controller;

class Contents {

    /** tempet nyimpen view */
    private $view;

    /**
     * tempet nyimpen sementara instance dari class Contents
     *
     * @var $instance Contents
     */
    private static $instance;

    const type_header = 'header';
    const type_sidebar = 'sidebar';
    const type_footer = 'footer';

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function set_view( $view ) {
        $this->view = $view;
    }
    function remove_view() {
        $this->set_view( '' );
    }
    function get_view() {
        return $this->view;
    }
    function get_view_path() {
        return LRS_VIEW_ABS_PATH . DS . $this->view;
    }

    /**
     * @param $type
     * @param $name
     */
    function get( $type, $name = '' ) {
        $file_name = $type . ( '' == $name ? '' : '-' . $name  ) . Routes::script_ext;
        $file_path = $this->get_view_path() . DS . $file_name ;
        if( file_exists( $file_path ) ) include( $file_path );
    }

    /**
     * @param string $name
     */
    function get_header( $name = '' ) {
        $this->get( self::type_header, $name );
    }

    /**
     * @param string $name
     */
    function get_sidebar( $name = '' ) {
        $this->get( self::type_sidebar, $name );
    }

    /**
     * @param string $name
     */
    function get_footer( $name = '' ) {
        $this->get( self::type_footer, $name );
    }

    /**
     * @param string $name
     */
    function get_template( $name = '' ) {
        $this->get( $name );
    }

}