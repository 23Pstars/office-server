<?php

namespace LRS\OfficeServer\Controller;

class Footers {

    var $script = '';

    /** @var Footers $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * contoh :
     * add_script( 'jquery.min.js' )
     *
     * keluaran :
     * <script type="text/javascript" src="http://root/asset/js/jquery.min.js" />
     *
     * @param $file
     * @param $position
     * @return $this
     */
    function add_script( $file, $position = Headers::add_position_after ) {
        $src = LRS_SCRIPT_URI_PATH . DS . $file;
        switch( $position ) {
            case Headers::add_position_after : $this->script .= '<script type="text/javascript" src="' . $src . '"></script>' . PHP_EOL; break;
            case Headers::add_position_before : $this->script = '<script type="text/javascript" src="' . $src . '"></script>' . PHP_EOL . $this->script; break;
        }
        return $this;
    }

    function get_script() {
        return $this->script;
    }

}