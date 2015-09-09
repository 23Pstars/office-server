<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;


class UDH {

    const length = '05';
    const identified = '00';
    const header = '03';
    const reference = '00';

    private $length;
    private $identified;
    private $header;
    private $reference;
    private $count;
    private $part;

    public function __construct( $udh ) {
        $this->length = substr( $udh, 0, 2 );
        $this->identified = substr( $udh, 2, 2 );
        $this->header = substr( $udh, 4, 2 );
        $this->reference = substr( $udh, 6, 2 );
        $this->count = substr( $udh, 8, 2 );
        $this->part = substr( $udh, 10, 2 );
    }

    public function _get_build() {
        return $this->length.$this->identified.$this->header.$this->reference.$this->count.$this->part;
    }

    public function _compare( $udh ) {

    }

    public function setCount($count) {
        $this->count = $count;
        return $this;
    }

    /**
     * @param bool $dec
     * @return number
     */
    public function getCount( $dec = false ) {
        return $dec ? $this->hex2dec( $this->count ) : $this->count;
    }

    /**
     * @param $header
     * @return $this
     */
    public function setHeader($header) {
        $this->header = $header;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @param $identified
     * @return $this
     */
    public function setIdentified($identified) {
        $this->identified = $identified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentified() {
        return $this->identified;
    }

    /**
     * @param $length
     * @return $this
     */
    public function setLength($length) {
        $this->length = $length;
        return $this;
    }

    /**
     * @param bool $dec
     * @return mixed
     */
    public function getLength( $dec = false ) {
        return $dec ? $this->hex2dec( $this->length ) : $this->length;
    }

    /**
     * @param $part
     * @param bool $dec
     * @return $this
     */
    public function setPart( $part, $dec = false ) {
        $this->part = $dec ? $this->dec2hex( $part ) : $part;
        return $this;
    }

    /**
     * @param bool $dec
     * @return mixed
     */
    public function getPart( $dec = false ) {
        return $dec ? $this->hex2dec( $this->part ) : $this->part;
    }

    /**
     * @param $reference
     * @return $this
     */
    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference() {
        return $this->reference;
    }

    public function hex2dec($hex)
    {
        return hexdec( $hex );
    }

    public function dec2hex($dec)
    {
        return ( $dec <= 15 ? '0' : '' ) . dechex( $dec );
    }

} 