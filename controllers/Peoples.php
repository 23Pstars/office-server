<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\People as ModelPeople;


class Peoples extends Databases {

    static $status = array( 0 => 'Tidak Aktif', 1 => 'Aktif' );

    private $field_id;
    private $class_name;
    private $table_name;
    private $count_query;

    /** @var Peoples $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        parent::_connect();
        $this->field_id = 'id';
        $this->class_name = '\LRS\OfficeServer\Model\People';
        $this->table_name = 'peoples';
        $this->count_query = 'SELECT * FROM ' . $this->table_name;
    }

    /**
     * @param $id
     * @param string $by
     * @return object|\stdClass
     */
    function _get( $id, $by = '' ) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE `' . ( empty( $by ) ? $this->field_id : $by ) . '` = "' . $id . '"';
        return ( $return = mysql_fetch_object(
            mysql_query( $query ), $this->class_name
        ) ) ? $return : new $this->class_name;
    }

    function _gets( $args = array() ) {

        $return = array();

        $default_args = array(
            'full_name'                 => '',
            'username'                  => '',
            'status'                    => -1,
            'exclude'                   => array(),
            'conditions'                => '',
            'orderby'                   => $this->field_id,
            'order'                     => 'DESC',
            'number'                    => 10,
            'offset'                    => 0
        );

        $list_args = sync_default_params( $default_args, $args );
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE 1';

        /**
         * full name
         */
        if( !empty( $list_args[ 'full_name' ] ) ) {
            $query .= ' AND ( ';
            $name_arr = explode( ' ', $list_args[ 'full_name' ] );
            foreach( $name_arr as $key => $name )
                $query .= ( $key > 0 ? ' OR' : '' ) . $this->table_name . '.`first_name` LIKE "%' . $name . '%"';
            $query .= ' OR ';
            foreach( $name_arr as $key => $name )
                $query .= ( $key > 0 ? ' OR' : '' ) . $this->table_name . '.`last_name` LIKE "%' . $name . '%"';
            $query .= ' )';
        }

        /**
         * username
         */
        if( !empty( $list_args[ 'username' ] ) )
            $query .= ' AND username = "' . $list_args[ 'username' ] . '"';

        /**
         * status
         */
        if( $list_args[ 'status' ] >= 0 )
            $query .= ' AND status = "' . $list_args[ 'status' ] . '"';

        /**
         * exclude
         */
        if( !empty( $list_args[ 'exclude' ] ) ) {

            foreach( $list_args[ 'exclude' ] as $ex )
                $query .= ' AND `' . $this->field_id . '` <> ' . $ex;

        }

        /**
         * untuk custom query pada conditions
         */
        if( !empty( $list_args[ 'conditions' ] ) ) {
            foreach( $list_args[ 'conditions' ] as $conditions )
                $query .= ' AND ' . $conditions[ 'field' ] . ' ' . $conditions[ 'operator' ] . ' ' . $conditions[ 'comparison' ];
        }

        $this->count_query = $query;

        /**
         * orderby dan jenis order
         */
        $query .= ' ORDER BY `' . $list_args[ 'orderby' ] . '` ' . $list_args[ 'order' ];

        /**
         * limit
         */
        if( $list_args[ 'number' ] >= 0 )
            $query .= ' LIMIT ' . $list_args[ 'offset' ] . ', ' . $list_args[ 'number' ];

        $resource = mysql_query( $query );

        //echo $query . ' : ' . mysql_error();

        while( $row = mysql_fetch_object( $resource, $this->class_name ) )
            $return[] = $row;

        return $return;

    }

    function _has( $username ) {
        $tmp = $this->_get( $username );
        return !( '' == $tmp->getUsername() || is_null( $tmp->getUsername() ) ) ? $tmp : false;
    }

    function _insert( ModelPeople $a ) {
        return parent::insert(
            $this->table_name,
            array(
                'first_name',
                'last_name',
                'birthday',
                'address',
                'phone',
                'email',
                'photo',
                'position_type',
                'position_description',
                'username',
                'password',
                'status'
            ),
            array(
                array(
                    $a->getFirstName(),
                    $a->getLastName(),
                    $a->getBirthday(),
                    $a->getAddress(),
                    $a->getPhone(),
                    $a->getEmail(),
                    $a->getPhoto(),
                    $a->getPositionType(),
                    $a->getPositionDescription(),
                    $a->getUsername(),
                    $a->getPassword(),
                    $a->getStatus()
                )
            )
        );
    }

    function _update( ModelPeople $a ) {
        return parent::update(
            $this->table_name,
            array(
                'first_name',
                'last_name',
                'birthday',
                'address',
                'phone',
                'email',
                'photo',
                'position_type',
                'position_description',
                'username',
                'password',
                'status'
            ),
            array(
                $a->getFirstName(),
                $a->getLastName(),
                $a->getBirthday(),
                $a->getAddress(),
                $a->getPhone(),
                $a->getEmail(),
                $a->getPhoto(),
                $a->getPositionType(),
                $a->getPositionDescription(),
                $a->getUsername(),
                $a->getPassword(),
                $a->getStatus()
            ),
            array(
                $this->field_id, $a->getId()
            )
        );
    }

    function _delete( $id ) {
        return parent::delete(
            $this->table_name, $this->field_id, $id
        );
    }

    function _count() {
        return mysql_num_rows(
            mysql_query( $this->count_query )
        );
    }

    function _valid( ModelPeople $a ) {
        return mysql_num_rows(
            mysql_query(
                'SELECT * FROM ' . $this->table_name . ' WHERE 1' .
                ' AND status = "' . $a->getStatus() . '"' .
                ' AND username = "' . $a->getUsername() . '"' .
                ' AND password = "' . $a->getPassword() . '"'
            )
        );
    }

} 