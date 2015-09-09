<?php

namespace LRS\OfficeServer\Controller;

class Options extends Databases {

    private $table_name;

    /** @var Options $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        parent::_connect();
        $this->table_name = 'options';
    }

    /**
     * Untuk mendapatkan value dari option yang telah tersimpan
     * didalam database
     *
     * @param $name
     * @param string $alternative_value
     * @param string $object
     * @return string
     */
    function get_option( $name, $alternative_value = '', $object = '' ) {
        $value = 'value';
        $result = array();
        $query = 'SELECT ' . $value . ' FROM `' . $this->table_name . '` WHERE name = "' . $name . '"';
        if( '' != $object ) $query .= ' AND object = "' . $object . '"';
        $resource = mysql_query( $query );
        if( $resource ) $result = mysql_fetch_assoc( $resource );
        return $result ? $result[ $value ] : $alternative_value;
    }

    /**
     * Fungsi untuk men-set nilai option sesuai dengan nama / key nya
     * jika key sudah ada => update
     * jika key belum ada => insert
     *
     * @param $name
     * @param $value
     * @param string $object
     * @return bool
     */
    function set_option( $name, $value, $object = '' ) {
        $query = 'SELECT * FROM `' . $this->table_name . '` WHERE name = "' . $name . '"';
        if( '' != $object ) $query .= ' AND object = "' . $object . '"';
        $obj_option = mysql_fetch_object( mysql_query( $query ) );
        if( $obj_option ) return parent::update( $this->table_name, array( 'value' ), array( $value ), array( 'id', $obj_option->id ) );
        else return parent::insert( $this->table_name, array( 'name', 'value', 'object' ), array( array( $name, $value, $object ) ) );
    }

}