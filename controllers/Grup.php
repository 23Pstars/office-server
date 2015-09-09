<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\Grup as ModelGrup;

class Grup extends Databases {

    const grop_ungouped = '--';

    private $field_id;
    private $class_name;
    private $count_query;

    /** @var Grup $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        parent::_connect();
        $this->field_id = 'ID';
        $this->class_name = '\LRS\OfficeServer\Model\Grup';
        $this->table_name = 'pbk_groups';
        $this->count_query = 'SELECT * FROM ' . $this->table_name;
    }

    /**
     * @param $id
     * @param string $by
     * @return ModelGrup
     */
    function _get( $id, $by = '' ) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE `' . ( empty( $by ) ? $this->field_id : $by ) . '` = "' . $id . '"';
        return mysql_fetch_object( mysql_query( $query ), $this->class_name );
    }

    function _gets( $args = array() ) {

        $return = array();

        $default_args = array(
            'Name'                      => '',
            'exclude'                   => array(),
            'conditions'                => '',
            'orderby'                   => $this->field_id,
            'order'                     => 'DESC',
            'number'                    => 10,
            'offset'                    => 0
        );

        $list_args = sync_default_params( $default_args, $args );
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE 1';

        if( !empty( $list_args[ 'Name' ] ) ) {

            $query .= ' AND ';
            $post_title_arr = explode( ' ', $list_args[ 'Name' ] );
            foreach( $post_title_arr as $key => $title )
                $query .= ( $key > 0 ? ' OR' : '' ) . ' ' . $this->table_name . '.`Name` LIKE "%' . $title . '%"';

        }

        /**
         * exclude
         */
        if( !empty( $list_args[ 'exclude' ] ) ) {

            foreach( $list_args[ 'exclude' ] as $ex )
                $query .= ' AND `kode` <> ' . $ex;

        }

        /**
         * untuk custom query pada conditions
         */
        if( !empty( $list_args[ 'conditions' ] ) ) {
            foreach( $list_args[ 'conditions' ] as $conditions )
                $query .= ' AND ' . $list_args . '.' . $conditions[ 'field' ] . ' ' . $conditions[ 'operator' ] . ' ' . $conditions[ 'comparison' ];
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

    function _insert( ModelGrup $a ) {
        return parent::insert(
            $this->table_name,
            array(
                'ID',
                'Name',
            ),
            array(
                array(
                    $a->getID(),
                    $a->getName(),
                )
            )
        );
    }

    function _update( ModelGrup $a ) {
        return parent::update(
            $this->table_name,
            array(
                'ID',
                'Name',
            ),
            array(
                $a->getID(),
                $a->getName(),
            ),
            array(
                'ID', $a->getID()
            )
        );
    }

    function _delete( $ID ) {
        return parent::delete( $this->table_name, 'ID', $ID );
    }

    function _count() {
        return mysql_num_rows( mysql_query( $this->count_query ) );
    }

} 