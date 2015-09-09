<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;


class PesanKeluarBagian {

    private $Text;
    private $Coding;
    private $UDH;
    private $Class;
    private $TextDecoded;
    private $ID;
    private $SequencePosition;

    /**
     * @param mixed $Class
     */
    public function setClass($Class)
    {
        $this->Class = $Class;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->Class;
    }

    /**
     * @param mixed $Coding
     */
    public function setCoding($Coding)
    {
        $this->Coding = $Coding;
    }

    /**
     * @return mixed
     */
    public function getCoding()
    {
        return $this->Coding;
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
     * @param mixed $SequencePosition
     */
    public function setSequencePosition($SequencePosition)
    {
        $this->SequencePosition = $SequencePosition;
    }

    /**
     * @return mixed
     */
    public function getSequencePosition()
    {
        return $this->SequencePosition;
    }

    /**
     * @param mixed $Text
     */
    public function setText($Text)
    {
        $this->Text = $Text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->Text;
    }

    /**
     * @param mixed $TextDecoded
     */
    public function setTextDecoded($TextDecoded)
    {
        $this->TextDecoded = $TextDecoded;
    }

    /**
     * @return mixed
     */
    public function getTextDecoded()
    {
        return $this->TextDecoded;
    }

    /**
     * @param mixed $UDH
     */
    public function setUDH($UDH)
    {
        $this->UDH = $UDH;
    }

    /**
     * @return mixed
     */
    public function getUDH()
    {
        return $this->UDH;
    }



} 