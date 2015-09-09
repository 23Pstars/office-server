<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

use LRS\OfficeServer\Model\People;
use LRS\OfficeServer\Model\Session;

class Authentications extends Databases {

    const success = 1, error = 0;

    /**
     * validasi masukan :
     * - huruf a-z besar atau kecil
     * - angka 0-9
     * - underscode (_)
     * - gak boleh spasi
     * - gak boleh karakter selain itu
     *
     * @var string
     */
    private $regex = '/^[a-zA-Z0-9_]+$/';

    static $error_messages = array(
        self::success => 'Berhasil masuk',
        self::error => 'Username dan Password tidak valid'
    );

    private $obj_people;

    /** @var Authentications $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        $this->obj_people = new People();
    }

    /**
     * amankan inputan username dan password
     * yang disubmit user
     *
     * @param $username
     * @param $password
     * @param $status
     * @return $this
     */
    function _init( $username, $password, $status = 1 ){
        $this->obj_people
            ->setUsername( $username )
            ->setPassword( $password )
            ->setStatus( $status );
        return $this;
    }

    function _valid_input() {
        return preg_match(
            $this->regex, $this->obj_people->getUsername()
        );
    }

    /**
     * @param string $redirect_while_success
     * @return int
     */
    function _auth( $redirect_while_success = '' ) {

        /**
         * cek masukan yang diberikan
         */
        $status = $this->_valid_input();

        /**
         * cek username, password, dan status didalam database
         */
        !$status || $status = Peoples::get_instance()->_valid( $this->obj_people );

        /**
         * jika ada, re-declare objek people dengan data yang ada didalam database
         */
        !$status || $this->obj_people = Peoples::get_instance()->_get( $this->obj_people->getUsername(), 'username' );

        if( $status ) {

            Sessions::get_instance()->_generate(
                Session::get_instance()->
                    _init( $this->obj_people )
            );

            /**
             * jika target redirect di-set
             */
            if( !empty( $redirect_while_success ) ) lrs_redirect( $redirect_while_success . '?' . Helpers::status_param . '=' . $status );
        }

        /**
         * sisanya kembalikan status return-nya
         */
        return $status;

    }

} 