<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;

use LRS\OfficeServer\Controller\Grup as ControllerGrup;

class Kontak {

    private $ID;
    private $GroupID;
    private $GroupName;
    private $Name;
    private $Number;

    function _init( $request ) {
        !isset( $request[ 'ID' ] ) || $this->ID = $request[ 'ID' ];
        !isset( $request[ 'GroupID' ] ) || $this->GroupID = $request[ 'GroupID' ];
        !isset( $request[ 'Name' ] ) || $this->Name = $request[ 'Name' ];
        !isset( $request[ 'Number' ] ) || $this->Number = $request[ 'Number' ];
    }

    /**
     * @param mixed $GroupName
     */
    public function setGroupName($GroupName)
    {
        $this->GroupName = $GroupName;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return is_null( $this->GroupName ) ? ControllerGrup::grop_ungouped : $this->GroupName;
    }

    /**
     * @param mixed $GroupID
     */
    public function setGroupID($GroupID)
    {
        $this->GroupID = $GroupID;
    }

    /**
     * @return mixed
     */
    public function getGroupID()
    {
        return $this->GroupID;
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

    /**
     * @param mixed $Number
     */
    public function setNumber($Number)
    {
        $this->Number = $Number;
    }

    /**
     * @return mixed
     */
    public function getNumber( $trail_plus = true )
    {
        return $trail_plus ? str_replace( '+', '', $this->Number ) : $this->Number;
    }

} 