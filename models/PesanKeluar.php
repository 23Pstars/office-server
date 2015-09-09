<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;

class PesanKeluar {

    private $UpdatedInDB;
    private $InsertIntoDB;
    private $SendingDateTime;
    private $SendBefore;
    private $SendAfter;
    private $Text;
    private $DestinationNumber;
    private $Coding;
    private $UDH;
    private $Class;
    private $TextDecoded;
    private $ID;
    private $MultiPart;
    private $RelativeValidity;
    private $SenderID;
    private $SendingTimeOut;
    private $DeliveryReport;
    private $CreatorID;

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
     * @param mixed $Class
     */
    public function setClass($Class)
    {
        $this->Class = $Class;
        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatorID()
    {
        return $this->CreatorID;
    }

    /**
     * @param mixed $DeliveryReport
     */
    public function setDeliveryReport($DeliveryReport)
    {
        $this->DeliveryReport = $DeliveryReport;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryReport()
    {
        return $this->DeliveryReport;
    }

    /**
     * @param mixed $DestinationNumber
     */
    public function setDestinationNumber($DestinationNumber)
    {
        $this->DestinationNumber = $DestinationNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDestinationNumber( $trail_plus = true )
    {
        return $trail_plus ? str_replace( '+', '', $this->DestinationNumber ) : $this->DestinationNumber;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $InsertIntoDB
     */
    public function setInsertIntoDB($InsertIntoDB)
    {
        $this->InsertIntoDB = $InsertIntoDB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInsertIntoDB()
    {
        return $this->InsertIntoDB;
    }

    /**
     * @param mixed $MultiPart
     */
    public function setMultiPart($MultiPart)
    {
        $this->MultiPart = $MultiPart;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMultiPart()
    {
        return $this->MultiPart;
    }

    public function isMultiPart() {
        return 'true' == $this->MultiPart;
    }

    /**
     * @param mixed $RelativeValidity
     */
    public function setRelativeValidity($RelativeValidity)
    {
        $this->RelativeValidity = $RelativeValidity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelativeValidity()
    {
        return $this->RelativeValidity;
    }

    /**
     * @param mixed $SendAfter
     */
    public function setSendAfter($SendAfter)
    {
        $this->SendAfter = $SendAfter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendAfter()
    {
        return $this->SendAfter;
    }

    /**
     * @param mixed $SendBefore
     */
    public function setSendBefore($SendBefore)
    {
        $this->SendBefore = $SendBefore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendBefore()
    {
        return $this->SendBefore;
    }

    /**
     * @param mixed $SenderID
     */
    public function setSenderID($SenderID)
    {
        $this->SenderID = $SenderID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSenderID()
    {
        return $this->SenderID;
    }

    /**
     * @param mixed $SendingDateTime
     */
    public function setSendingDateTime($SendingDateTime)
    {
        $this->SendingDateTime = $SendingDateTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendingDateTime()
    {
        return $this->SendingDateTime;
    }

    /**
     * @param mixed $SendingTimeOut
     */
    public function setSendingTimeOut($SendingTimeOut)
    {
        $this->SendingTimeOut = $SendingTimeOut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendingTimeOut()
    {
        return $this->SendingTimeOut;
    }

    /**
     * @param mixed $Text
     */
    public function setText($Text)
    {
        $this->Text = $Text;
        return $this;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextDecoded()
    {
        return $this->TextDecoded;
    }

    public function appendTextDecoded($TextDecoded)
    {
        $this->TextDecoded .= $TextDecoded;
        return $this;
    }

    /**
     * @param mixed $UDH
     */
    public function setUDH($UDH)
    {
        $this->UDH = $UDH;
        return $this;
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

    /**
     * @param mixed $UpdatedInDB
     */
    public function setUpdatedInDB($UpdatedInDB)
    {
        $this->UpdatedInDB = $UpdatedInDB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedInDB()
    {
        return $this->UpdatedInDB;
    }

} 