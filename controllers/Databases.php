<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;


class Databases {

    protected $db_conn = null;
    protected $db_select = null;

    /**
     * koneksi langsung saat class di instance
     *
     * @author Zaf
     */
    function __construct() {
        $this->_connect();
    }

    /**
     * koneksi ke database
     */
    function _connect(){
        if( !$this->db_conn = mysql_connect( LRS_DB_HOST, LRS_DB_USERNAME, LRS_DB_PASSWORD ) )
            lrs_exit( 'Ndeq ne bau konek kadu User : ' . LRS_DB_USERNAME );

        if( !$this->db_select = mysql_select_db( LRS_DB_NAME, $this->db_conn ) )
            lrs_exit( 'Ndek ne bau konek jok DB : ' . LRS_DB_NAME );
    }

    /**
     * fungsi buat insert value
     *
     * ex :
     *
     * $this->insert(
     *      'nama_tabel',
     *      array( 'kolom1', 'kolom2' ),
     *      array(
     *          array( 'nilai 1 kolom 1', 'nilai 1 kolim 2' )
     *          array( 'nilai 2 kolom 1', 'nilai 2 kolim 2' )
     *          array( 'nilai 3 kolom 1', 'nilai 3 kolim 2' )
     *      )
     * )
     *
     * @author Zaf
     * @param string $table
     * @param array $column
     * @param array $value
     * @return bool $res
     */
    function insert( $table, $column, $value ) {

        $c_value = count( $value );
        $cr_value = count( $value, COUNT_RECURSIVE );

        /**
         * karena strukturnya multidimensi array
         */
        $is_condition_multi = $c_value != $cr_value;

        /**
         * cek minimal ada 1 value yang sama dengan jumlah kolom
         * jumlah kolom seharusnya sama dengan jumlah data dalam tiap value (array)
         * karena fungsi ini dapat melakukan insert beberapa value dalam sekali eksekusi
         */

        /** kolom */
        if( !is_array( $column )

            /** value */
            || !is_array( $value )

            /** apakah value multidimensi */
            || !$is_condition_multi

            /** cek apakah jumlahnya sama sama value pertama */
            || count( $column ) != count( $value[0] ) )

            lrs_exit( 'Error while execute `insert` function, invalid params or column and value count mismatch.' );

        /**
         * query dasar
         */
        $query = 'INSERT INTO `' . $table . '` ( `' . implode( '`, `', $column ) . '` ) VALUES ';

        /**
         * hitung jumlah array dari parameter value
         */
        $number_of_value = count( $value );

        foreach( $value as $k => $v )
            $query .= '("' . implode( '", "', $v ) . '")' . ( $k < ( $number_of_value - 1 ) ? ', ' : '' );

        $result = mysql_query( $query );
        $result || error_log( mysql_error() );

        return $result;

    }

    /**
     * fungsi standar buat update value,
     *
     * ex :
     *
     * $this->update(
     *      'nama_tabel',
     *      array( 'kolom1', 'kolom2' ),
     *      array( 'nilai1', 'nilai2' ),
     *      array(
     *          array( '', 'kolom1', 'kondisi1' ),      -> operator untuk array 1 dikosongkan
     *          array( 'AND', 'kolom2', 'kondisi' )
     *      )
     * )
     *
     *
     * @author Zaf
     * @param string $table
     * @param array $column
     * @param array $value
     * @param array $condition
     * @return bool $res
     */
    function update( $table, $column, $value, $condition ) {

        $c_column = count( $column );
        $c_value = count( $value );
        $c_condition = count( $condition );
        $cr_condition = count( $condition, COUNT_RECURSIVE );       // recursive

        /**
         * karena strukturnya multidimensi array
         */
        $is_condition_multi = $c_condition != $cr_condition;

        /**
         * sama seperti insert, pastikan jumlah kolom dan nilai tiap value sama
         */
        if( $c_column != $c_value

            /**
             * untuk kondisi yang bukan multi condition, pastikan jumlah array-nya hanya 2
             */
            || ( !$is_condition_multi && 2 != $c_condition && !is_array( $condition ) ) )

            lrs_exit( 'Error while execute `update` function, invalid params or column and value count mismatch.' );

        /**
         * query dasar
         */
        $query = 'UPDATE `' . $table . '` SET';

        /**
         * bentuk query
         */
        foreach( $column as $k => $c )
            $query .= ' `' . $c . '` = "' . $value[ $k ] . '"' . ( $c != end( $column ) ? ', ' : '' );

        /**
         * kondisi-kondisi
         */
        $query .= ' WHERE';

        if( $is_condition_multi )
            foreach( $condition as $d ) $query .= ' ' . $d[ 0 ] . ' `' . $d[ 1 ] . '` = "' . $d[ 2 ] . '"';
        else
            $query .= ' `' . $condition[ 0 ] . '` = "' . $condition[ 1 ] . '"';

        $result = mysql_query( $query );
        $result || error_log( mysql_error() );

        return $result;

    }

    /**
     * fungsi buat delete value
     *
     * @author Zaf
     * @param string $table
     * @param string $column
     * @param string $value
     * @return bool $res
     */
    function delete( $table, $column, $value ) {
        $result = mysql_query( 'DELETE FROM `' . $table . '` WHERE `' . $column . '` = "' . $value . '"' );
        $result || error_log( mysql_error() );
        return $result;
    }
} 