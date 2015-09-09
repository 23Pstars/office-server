<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;

use LRS\OfficeServer\Controller\Inbox;

class PesanMasuk {
    private $UpdatedInDB;
    private $ReceivingDateTime;
    private $Text;
    private $SenderNumber;
    private $Coding;
    private $UDH;
    private $SMSCNumber;
    private $Class;
    private $TextDecoded;
    private $ID;
    private $RecipientID;
    private $Processed;

    private $parts = array();
    private $ContactName;
    private $RepliedTextDecoded;
    private $RepliedSendingDateTime;

    /**
     * @param mixed $RepliedSendingDateTime
     */
    public function setRepliedSendingDateTime($RepliedSendingDateTime)
    {
        $this->RepliedSendingDateTime = $RepliedSendingDateTime;
    }

    /**
     * @return mixed
     */
    public function getRepliedSendingDateTime()
    {
        return $this->RepliedSendingDateTime;
    }

    /**
     * @param mixed $RepliedTextDecoded
     */
    public function setRepliedTextDecoded($RepliedTextDecoded)
    {
        $this->RepliedTextDecoded = $RepliedTextDecoded;
    }

    /**
     * @return mixed
     */
    public function getRepliedTextDecoded()
    {
        return $this->RepliedTextDecoded;
    }

    /**
     * @return bool
     */
    public function hasRepliedTextDecoded()
    {
        return '' != $this->RepliedTextDecoded;
    }

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
     * @param mixed $Processed
     */
    public function setProcessed($Processed)
    {
        $this->Processed = $Processed;
    }

    /**
     * @return mixed
     */
    public function getProcessed()
    {
        return $this->Processed;
    }

    public function hasProcessedFalse() {
        return Inbox::processed_false == $this->Processed;
    }

    public function hasProcessedTrue() {
        return Inbox::processed_true == $this->Processed;
    }

    public function hasProcessedDisplay() {
        return Inbox::processed_display == $this->Processed;
    }

    /**
     * @param mixed $ReceivingDateTime
     */
    public function setReceivingDateTime($ReceivingDateTime)
    {
        $this->ReceivingDateTime = $ReceivingDateTime;
    }

    /**
     * @return mixed
     */
    public function getReceivingDateTime($format='Y-m-d H:i:s')
    {
        return date( $format, strtotime( $this->ReceivingDateTime ) );
    }

    /**
     * @param mixed $RecipientID
     */
    public function setRecipientID($RecipientID)
    {
        $this->RecipientID = $RecipientID;
    }

    /**
     * @return mixed
     */
    public function getRecipientID()
    {
        return $this->RecipientID;
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
     * @param mixed $SenderNumber
     */
    public function setSenderNumber($SenderNumber)
    {
        $this->SenderNumber = $SenderNumber;
    }

    /**
     * @return mixed
     */
    public function getSenderNumber( $trail_number = true, $sensor = 0, $sensor_char_replacement = 'x' )
    {
        $out = $trail_number ? str_replace( '+', '', $this->SenderNumber ) : $this->SenderNumber;
        !$sensor || $out = substr( $out, 0, ($sensor*-1) ) . str_repeat( $sensor_char_replacement, $sensor );
        return $out;
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

    public function getAppendedTextDecoded( $offset = 0 ) {
        $text_decoded = $this->TextDecoded;
        /** @var $part PesanMasuk */
        foreach( $this->parts as $part )
            $text_decoded .= $part->getTextDecoded();
        return $offset ? substr( $text_decoded, 0, $offset ) . ( strlen( $text_decoded ) > $offset ? ' ...' : '' ) : $text_decoded;
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

} 