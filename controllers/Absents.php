<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\Absent;


class Absents extends Databases {

    /**
     * default
     */
    const status_tidak_masuk = 'tidak-masuk';

    /**
     * setelah klik absen,
     * jika lupa logout, maka status ini akan terus tidak berubah
     */
    const status_masuk = 'masuk';

    /**
     * mulai dan selesai tepat waktu
     */
    const status_selesai = 'selesai';

    /**
     * jika sakit
     * (nanti ada fitur sms)
     */
    const status_sakit = 'sakit';

    /**
     * jika izin
     * (nanti ada fitur sms)
     */
    const status_izin = 'izin';

    /**
     * jika izin pulang dan status sebelumnya sudah masuk
     */
    const status_izin_pulang = 'izin-pulang';

    const aksi_mulai = 'mulai';
    const aksi_berhenti = 'berhenti';
    const aksi_berhenti_sebelum_waktunya = 'berhenti-sw';

    private $field_id;
    private $class_name;
    private $table_name;
    private $table_people;
    private $count_query;

    /** @var Absents $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        parent::_connect();
        $this->field_id = 'date';
        $this->class_name = '\LRS\OfficeServer\Model\Absent';
        $this->table_name = 'absents';
        $this->table_people = 'peoples';
        $this->count_query = 'SELECT * FROM ' . $this->table_name;
    }

    /**
     * @param $date
     * @param $people_id
     * @return Absent
     */
    function _get( $date, $people_id ) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE `' . $this->field_id . '` = "' . $date . '" AND `people_id` = "' . $people_id . '"';
        return ( $return = mysql_fetch_object(
            mysql_query( $query ), $this->class_name
        ) ) ? $return : new $this->class_name;
    }

    function _gets( $args = array() ) {

        $return = array();

        $default_args = array(
            'range_date_start'          => '',
            'range_date_end'            => '',
            'people_id'                 => -1,
            'status'                    => -1,
            'note'                      => '',
            'exclude'                   => array(),
            'conditions'                => '',
            'orderby'                   => $this->field_id,
            'order'                     => 'DESC',
            'number'                    => 10,
            'offset'                    => 0
        );

        $list_args = sync_default_params( $default_args, $args );
        $query = 'SELECT *';
        $query .= ', ' . $this->table_name . '.status AS status_kerja';
        $query .= ' FROM ' . $this->table_name;
        $query .= ' LEFT JOIN ' . $this->table_people . ' ON ' . $this->table_name . '.people_id = ' . $this->table_people . '.id';
        $query .= ' WHERE 1';

        /**
         * range date start and end
         */
        if( !empty( $list_args[ 'range_date_start' ] ) )
            $query .= ' AND transfer_date >= "' . $list_args[ 'range_date_start' ] . '"';
        if( !empty( $list_args[ 'range_date_end' ] ) )
            $query .= ' AND transfer_date <= "' . $list_args[ 'range_date_end' ] . '"';

        /**
         * people
         */
        if( $list_args[ 'people_id' ] >= 0 )
            $query .= ' AND people_id = "' . $list_args[ 'people_id' ] . '"';

        /**
         * status
         */
        if( $list_args[ 'status' ] >= 0 )
            $query .= ' AND status = "' . $list_args[ 'status' ] . '"';

        /**
         * note
         */
        if( !empty( $list_args[ 'note' ] ) ) {
            $query .= ' AND ';
            $name_arr = explode( ' ', $list_args[ 'note' ] );
            foreach( $name_arr as $key => $name )
                $query .= ( $key > 0 ? ' OR' : '' ) . $this->table_name . '.`note` LIKE "%' . $name . '%"';
        }

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

    function _has( $date, $people_id ) {
        $tmp = $this->_get( $date, $people_id );
        return !( '1970-01-01' == $tmp->getDate() || '' == $tmp->getDate() || is_null( $tmp->getDate() ) ) ? $tmp : false;
    }

    function _insert( Absent $a ) {
        return parent::insert(
            $this->table_name,
            array(
                'date',
                'worktime_start',
                'worktime_end',
                'people_id',
                'status',
                'note'
            ),
            array(
                array(
                    $a->getDate(),
                    $a->getWorktimeStart(),
                    $a->getWorktimeEnd(),
                    $a->getPeopleId(),
                    $a->getStatus(),
                    $a->getNote()
                )
            )
        );
    }

    function _update( Absent $a ) {
        if( $this->_has( $a->getDate(), $a->getPeopleId() ) ) {
            return parent::update(
                $this->table_name,
                array(
                    'worktime_start',
                    'worktime_end',
                    'people_id',
                    'status',
                    'note'
                ),
                array(
                    $a->getWorktimeStart(),
                    $a->getWorktimeEnd(),
                    $a->getPeopleId(),
                    $a->getStatus(),
                    $a->getNote()
                ),
                array(
                    array( '', 'date', $a->getDate() ),
                    array( 'AND', 'people_id', $a->getPeopleId() )
                )
            );
        } else return $this->_insert( $a );
    }

    function _delete( $date ) {
        return parent::delete(
            $this->table_name, $this->field_id, $date
        );
    }

    function _count() {
        return mysql_num_rows(
            mysql_query( $this->count_query )
        );
    }

} 