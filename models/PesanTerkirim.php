<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;


class PesanTerkirim {

    private $UpdatedInDB;
    private $InsertIntoDB;
    private $SendingDateTime;
    private $DeliveryDateTime;
    private $Text;
    private $DestinationNumber;
    private $Coding;
    private $UDH;
    private $SMSCNumber;
    private $Class;
    private $TextDecoded;
    private $ID;
    private $SenderID;
    private $SequencePosition;
    private $Status;
    private $StatusError;
    private $TPMR;
    private $RelativeValidity;
    private $CreatorID;

    private $parts;
    private $ContactName;

    /**
     * @param mixed $ContactName
     */
    public function setContactName($ContactName)
    {
        $this->ContactName = $ContactName;
    }

    /**
     * @return mixed
     */
    public function getContactName()
    {
        return $this->ContactName;
    }

    public function hasContactName()
    {
        return !is_null( $this->ContactName );
    }

    /**
     * @param mixed $parts
     */
    public function setParts($parts)
    {
        $this->parts = $parts;
    }

    /**
     * @return mixed
     */
    public function getParts()
    {
        return $this->parts;
    }

    public function addParts($parts)
    {
        $this->parts[] = $parts;
    }

    /**
     * @param mixed $InsertIntoDB
     */
    public function setInsertIntoDB($InsertIntoDB)
    {
        $this->InsertIntoDB = $InsertIntoDB;
    }

    /**
     * @return mixed
     */
    public function getInsertIntoDB()
    {
        return $this->InsertIntoDB;
    }

    /**
     * @param mixed $SendingDateTime
     */
    public function setSendingDateTime($SendingDateTime)
    {
        $this->SendingDateTime = $SendingDateTime;
    }

    /**
     * @return mixed
     */
    public function getSendingDateTime()
    {
        return $this->SendingDateTime;
    }

    /**
     * @param mixed $UpdatedInDB
     */
    public function setUpdatedInDB($UpdatedInDB)
    {
        $this->UpdatedInDB = $UpdatedInDB;
    }

    /**
     * @return mixed
     */
    public function getUpdatedInDB()
    {
        return $this->UpdatedInDB;
    }

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
     * @param mixed $CreatorID
     */
    public function setCreatorID($CreatorID)
    {
        $this->CreatorID = $CreatorID;
    }

    /**
     * @return mixed
     */
    public function getCreatorID()
    {
        return $this->CreatorID;
    }

    /**
     * @param mixed $DeliveryDateTime
     */
    public function setDeliveryDateTime($DeliveryDateTime)
    {
        $this->DeliveryDateTime = $DeliveryDateTime;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDateTime()
    {
        return $this->DeliveryDateTime;
    }

    /**
     * @param mixed $DestinationNumber
     */
    public function setDestinationNumber($DestinationNumber)
    {
        $this->DestinationNumber = $DestinationNumber;
    }

    /**
     * @return mixed
     */
    public function getDestinationNumber()
    {
        return $this->DestinationNumber;
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
     * @param mixed $RelativeValidity
     */
    public function setRelativeValidity($RelativeValidity)
    {
        $this->RelativeValidity = $RelativeValidity;
    }

    /**
     * @return mixed
     */
    public function getRelativeValidity()
    {
        return $this->RelativeValidity;
    }

    /**
     * @param mixed $SMSCNumber
     */
    public function setSMSCNumber($SMSCNumber)
    {
        $this->SMSCNumber = $SMSCNumber;
    }

    /**
     * @return mixed
     */
    public function getSMSCNumber()
    {
        return $this->SMSCNumber;
    }

    /**
     * @param mixed $SenderID
     */
    public function setSenderID($SenderID)
    {
        $this->SenderID = $SenderID;
    }

    /**
     * @return mixed
     */
    public function getSenderID()
    {
        return $this->SenderID;
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
     * @param mixed $Status
     */
    public function setStatus($Status)
    {
        $this->Status = $Status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @param mixed $StatusError
     */
    public function setStatusError($StatusError)
    {
        $this->StatusError = $StatusError;
    }

    /**
     * @return mixed
     */
    public function getStatusError()
    {
        return $this->StatusError;
    }

    /**
     * @param mixed $TPMR
     */
    public function setTPMR($TPMR)
    {
        $this->TPMR = $TPMR;
    }

    /**
     * @return mixed
     */
    public function getTPMR()
    {
        return $this->TPMR;
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

    public function hasUDH() {
        return '' != $this->UDH;
    }

} 