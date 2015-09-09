<?php
/**
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;

class Grup {

    private $ID;
    private $Name;

    function _init( $request ) {
        !isset( $request[ 'ID' ] ) || $this->ID = $request[ 'ID' ];
        !isset( $request[ 'Name' ] ) || $this->Name = $request[ 'Name' ];
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

} 