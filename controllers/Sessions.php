<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\Session;

class Sessions {

    const name = '___lrsoft';

    function __construct() {

        /**
         * mulai session disini
         */
        session_start();

    }

    /** @var Sessions $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function _generate( $obj_session ) {
        $_SESSION[ self::name ] = $obj_session;
    }

    function _destroy() {
        unset( $_SESSION[ self::name ] );
    }

    /**
     * @return Session
     */
    function _retrieve() {
        return $_SESSION[ self::name ];
    }

    /**
     * @return bool|Session
     */
    function _has() {
        return isset( $_SESSION[ self::name ] );
    }

    function _validate( $redirect_while_failed = '' ){

        $valid = false;
        if( $this->_has() ) {

            /** @var Session $obj_session */
            $obj_session = $_SESSION[ self::name ];

            /**
             * untuk session yang di-cek adalah hanya user agent dan alamat ip;
             * menghindari session di-copy dan digunakan di komputer lain
             *
             * username dan password tidak di validasi,
             * karena akan membutuhkan koneksi database setiap kali eksekusi
             */
            $valid = $obj_session->compareUserAgent( $_SERVER[ 'HTTP_USER_AGENT' ] );

            /**
             * mungkin tergantung koneksi,
             * beberapa ISP memberikan IP sangat dinamis, jadi tidak bisa melakukan
             * verifikasi berdasarkan IP
             */
            $valid = $valid && $obj_session->compareIpAddress( $_SERVER[ 'REMOTE_ADDR' ] );

            /**
             * jika sudah memasukin halaman view,
             * periksa kembali apakah sesuai dengan hak akses nya
             */
            '' == Contents::get_instance()->get_view()
            || $valid = $valid && ( $obj_session->getView() == Contents::get_instance()->get_view() );

        }

        if( !$valid ) {

            /**
             * jika tujuan redirect sudah ditentukan
             */
            if( !empty( $redirect_while_failed ) ) lrs_redirect( $redirect_while_failed );

        }

        /**
         * kembalikan hasil validasi
         */
        return $valid;
    }

} 