<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\PesanMasuk;
use LRS\OfficeServer\Model\UDH;

class Inbox extends Databases {

    const public_prefix = '[lrs]';
    const processed_false = 'false';
    const processed_true = 'true';
    const processed_display = 'display';

    private $field_id;
    private $class_name;
    private $table_pbk;
    private $table_sentitems;
    private $count_query;
    private $unread_count_query;
    private $public_unread_count_query;

    /** @var Inbox $instance */
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
        $this->class_name = '\LRS\OfficeServer\Model\PesanMasuk';
        $this->table_name = 'inbox';
        $this->table_pbk = 'pbk';
        $this->table_sentitems = 'sentitems';
        $this->count_query = 'SELECT * FROM ' . $this->table_name;
        $this->unread_count_query = 'SELECT * FROM ' . $this->table_name . ' WHERE Processed = "false"';
        $this->public_unread_count_query = 'SELECT * FROM ' . $this->table_name . ' WHERE TextDecoded LIKE "' . self::public_prefix . '%" AND Processed = "false"';
    }

    /**
     * @param $id
     * @param string $by
     * @return PesanMasuk
     */
    function _get( $id, $by = '' ) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE `' . ( empty( $by ) ? $this->field_id : $by ) . '` = "' . $id . '"';
        /** @var PesanMasuk $pesan */
        $pesan = mysql_fetch_object( mysql_query( $query ), $this->class_name );
        if( $pesan->hasUDH() ) {
            $udh = new UDH( $pesan->getUDH() );
            if( $udh->getPart( true ) == 1 ) {
                for( $i=2; $i<=$udh->getCount( true ); $i++ ) {
                    $pesan->addParts( $this->_get( $udh->setPart( $i, true )->_get_build(), 'UDH' ) );
                }
            }
        }
        return $pesan;
    }

    function _gets( $args = array() ) {

        $return = array();

        $default_args = array(
            'TextDecoded'               => '',
            'Processed'                 => -1,
            'public_prefix'             => '',
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
        $query .= ', ' . $this->table_sentitems . '.TextDecoded as RepliedTextDecoded';
        $query .= ', ' . $this->table_sentitems . '.SendingDateTime as RepliedSendingDateTime';
        $query .= ' FROM ' . $this->table_name;
        $query .= ' LEFT JOIN ' . $this->table_pbk . ' ON ' . $this->table_pbk . '.Number = REPLACE(' . $this->table_name . '.SenderNumber, "+", "")';
        $query .= ' LEFT JOIN ' . $this->table_sentitems . ' ON ' . $this->table_sentitems . '.CreatorID = ' . $this->table_name . '.ID';
        $query .= ' WHERE 1';

        if( !empty( $list_args[ 'TextDecoded' ] ) ) {
            $query .= ' AND ';
            $post_title_arr = explode( ' ', $list_args[ 'TextDecoded' ] );
            foreach( $post_title_arr as $key => $title )
                $query .= ( $key > 0 ? ' OR' : '' ) . $this->table_name . '.`TextDecoded` LIKE "%' . $title . '%"';
        }

        if( $list_args[ 'Processed' ] >= 0 )
            $query .= ' AND ' . $this->table_name . '.Processed = "' . $list_args[ 'Processed' ] . '"';

        if( !empty( $list_args[ 'public_prefix' ] ) ) {
            $query .= ' AND ' . $this->table_name . '.`TextDecoded` LIKE "' . $list_args[ 'public_prefix' ] . '%"';
        } else {
            $query .= ' AND ' . $this->table_name . '.`TextDecoded` NOT LIKE "' . self::public_prefix . '%"';
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

        /** @var PesanMasuk $row */
        while( $row = mysql_fetch_object( $resource, $this->class_name ) ) {
            if( $row->hasUDH() ) $return[ $row->getUDH() ] = $row;
            else $return[] = $row;
        }

        /** @var $pesan PesanMasuk */
        foreach( $return as $UDH => $pesan ) {
            if( $pesan->hasUDH() ) {
                $udh = new UDH( $UDH );
                if( $udh->getPart( true ) == 1 ) {
                    for( $i=2; $i<=$udh->getCount( true ); $i++ ) {
                        $tmp = $udh->setPart( $i, true )->_get_build();
                        $pesan->addParts( $return[ $tmp ] );
                        unset( $return[ $tmp ] );
                    }
                }
            }
        }

        return $return;

    }

    function _set_processed( $ID, $process = self::processed_false ) {
        return parent::update(
            $this->table_name,
            array( 'Processed' ),
            array( $process ),
            array( 'ID', $ID )
        );
    }

    function _delete( $ID ) {
        return parent::delete( $this->table_name, 'ID', $ID );
    }

    function _count() {
        return mysql_num_rows( mysql_query( $this->count_query ) );
    }

    function _unread_count() {
        return mysql_num_rows( mysql_query( $this->unread_count_query ) );
    }

    function _public_unread_count() {
        return mysql_num_rows( mysql_query( $this->public_unread_count_query ) );
    }

} 