<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;

class Securities {

    /**
     * salt default = "SIAKAD_UNRAM"
     */
    const salt = '$6$nuZMQ.8u$lUcZZIJrODLMHogP0oQhuqo0N8bS11cnSAIjbwyER29CoXuDhYlk5/X0GWLVLU4V9.Uzi3qF6QpS8YtQRUkjr0';

    /** @var Securities $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * sementara sampai dikembangkan untuk menggunakan model
     * enkripsi yang lain
     *
     * @param $str
     * @param $salt
     * @return bool|string
     */
    function encrypt( $str, $salt = self::salt ) {
        return crypt( $str, $salt );
    }

} 