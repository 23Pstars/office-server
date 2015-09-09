<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\UDH;
use LRS\OfficeServer\Model\PesanKeluar;
use LRS\OfficeServer\Model\PesanKeluarBagian;

class Outbox extends Databases {

    private $field_id;
    private $class_name;
    private $class_outbox_multipart;
    private $table_outbox_multipart;
    private $table_pbk;
    private $count_query;

    /** @var Outbox $instance */
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
        $this->class_name = '\LRS\OfficeServer\Model\PesanKeluar';
        $this->class_outbox_multipart = '\LRS\OfficeServer\Model\PesanKeluarBagian';
        $this->table_name = 'outbox';
        $this->table_outbox_multipart = 'outbox_multipart';
        $this->table_pbk = 'pbk';
        $this->count_query = 'SELECT * FROM ' . $this->table_name;
    }

    /**
     * @param $id
     * @param string $by
     * @return PesanKeluar
     */
    function _get( $id, $by = '' ) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE `' . ( empty( $by ) ? $this->field_id : $id ) . '` = "' . $id . '"';
        return ( $return = mysql_fetch_object(
            mysql_query( $query ), $this->class_name
        ) ) ? $return : new $this->class_name;
    }

    function _gets( $args = array() ) {

        $return = array();

        $default_args = array(
            'Text'                      => '',
            'exclude'                   => array(),
            'conditions'                => '',
            'orderby'                   => $this->field_id,
            'order'                     => 'DESC',
            'number'                    => 10,
            'offset'                    => 0
        );

        $list_args = sync_default_params( $default_args, $args );
        $query = 'SELECT ' . $this->table_name . '.*';
        $query .= ', ' . $this->table_pbk . '.Name as ContactName';
        $query .= ' FROM ' . $this->table_name;
        $query .= ' LEFT JOIN ' . $this->table_pbk . ' ON ' . $this->table_pbk . '.Number = REPLACE(' . $this->table_name . '.DestinationNumber, "+", "")';
        $query .= ' WHERE 1';


        if( !empty( $list_args[ 'Text' ] ) ) {

            $query .= ' AND ';
            $post_title_arr = explode( ' ', $list_args[ 'Text' ] );
            foreach( $post_title_arr as $key => $title )
                $query .= ( $key > 0 ? ' OR' : '' ) .  '`Text` LIKE "%' . $title . '%"';

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

        /** @var $pesan PesanKeluar */
        foreach( $return as $UDH => $pesan ) {
            if( $pesan->isMultiPart() ) {
                $parts = $this->_gets_part( $pesan->getID() );
                /** @var $part PesanKeluarBagian */
                foreach( $parts as $part ) $pesan->appendTextDecoded( $part->getTextDecoded() );
            }
        }

        return $return;

    }

    function _gets_part( $ID ) {
        $return = array();
        $res = mysql_query( 'SELECT * FROM ' . $this->table_outbox_multipart . ' WHERE ID = "' . $ID . '" ORDER BY `SequencePosition` ASC' );
        while( $row = mysql_fetch_object( $res, $this->class_outbox_multipart ) )
            $return[] = $row;
        return $return;
    }

    /**
     * Sementara belum support untuk multipart
     *
     * @param PesanKeluar $outbox
     * @return bool
     */
    function _insert( PesanKeluar $outbox ) {
        return parent::insert(
            $this->table_name,
            array(
                'DestinationNumber',
                'TextDecoded',
                'CreatorID'
            ),
            array(
                array(
                    $outbox->getDestinationNumber(),
                    $outbox->getTextDecoded(),
                    $outbox->getCreatorID()
                )
            )
        );
    }
    function _inserts( $array_outbox ) {
        return !is_array( $array_outbox ) ? false : parent::insert(
            $this->table_name,
            array(
                'DestinationNumber',
                'TextDecoded',
                'CreatorID'
            ),
            $array_outbox
        );
    }

    function _delete( $ID ) {
        return parent::delete( $this->table_name, 'ID', $ID );
    }

    function _count() {
        return mysql_num_rows( mysql_query( $this->count_query ) );
    }

} 