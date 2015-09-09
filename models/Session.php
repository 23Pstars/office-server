<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;

class Session {

    /** @var People $obj_people */
    private $obj_people;

    private $view;
    private $user_agent;
    private $ip_address;

    public function __construct() {
        $this
            ->setUserAgent( $_SERVER[ 'HTTP_USER_AGENT' ] )
            ->setIpAddress( $_SERVER[ 'REMOTE_ADDR' ] );
    }

    /** @var Session $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function _init( People $obj_people ) {
        $this->obj_people = $obj_people;
        $this->view = $this->obj_people->getPositionType();
        return $this;
    }

    /**
     * @return People
     */
    public function getObjPeople()
    {
        return $this->obj_people;
    }

    /**
     * @param $ip_address
     * @return $this
     */
    public function setIpAddress($ip_address)
    {
        $this->ip_address = $ip_address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @param $ip_address
     * @return bool
     */
    public function compareIpAddress( $ip_address )
    {
        return $this->ip_address == $ip_address;
    }

    /**
     * @param $user_agent
     * @return $this
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * @param $user_agent
     * @return bool
     */
    public function compareUserAgent( $user_agent )
    {
        return $this->user_agent == $user_agent;
    }

    /**
     * @param $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }



} 