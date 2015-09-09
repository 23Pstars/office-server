<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Model;

use LRS\OfficeServer\Controller\Absents;

class Absent {

    private $date;
    private $worktime_start;
    private $worktime_end;
    private $people_id;
    private $status;
    private $note;

    private $first_name;
    private $last_name;
    private $status_kerja;

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getStatusKerja()
    {
        return $this->status_kerja;
    }



    /**
     * @param $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param string $format
     * @return bool|string
     */
    public function getDate( $format = 'Y-m-d')
    {
        return date( $format, strtotime( $this->date ) );
    }

    /**
     * @param $note
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param $people_id
     * @return $this
     */
    public function setPeopleId($people_id)
    {
        $this->people_id = $people_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPeopleId()
    {
        return $this->people_id;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $worktime_end
     * @return $this
     */
    public function setWorktimeEnd($worktime_end)
    {
        $this->worktime_end = $worktime_end;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorktimeEnd()
    {
        return $this->worktime_end;
    }

    /**
     * @param $worktime_start
     * @return $this
     */
    public function setWorktimeStart($worktime_start)
    {
        $this->worktime_start = $worktime_start;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorktimeStart()
    {
        return $this->worktime_start;
    }

    public function getWorktimeLimit( $duration, $format = 'H:i:s' ) {
        $worktime_start = new \DateTime( $this->worktime_start );
        $worktime_start->add( new \DateInterval( 'PT' . $duration . 'M' ) );
        return $worktime_start->format( $format );
    }

    public function getWorktimeRest( $worktime_limit, $format = 'H:i:s' ) {
        $current_time = new \DateTime( date( $format ) );
        $worktime_limit = new \DateTime( $worktime_limit );
        $worktime_limit->diff( $current_time );
        return $worktime_limit->format( $format );
    }

    function isStatusTidakMasuk() {
        return $this->status == Absents::status_tidak_masuk;
    }
    function isStatusMasuk() {
        return $this->status == Absents::status_masuk;
    }
    function isStatusIzin() {
        return $this->status == Absents::status_izin;
    }
    function isStatusIzinPulang() {
        return $this->status == Absents::status_izin_pulang;
    }
    function isStatusSakit() {
        return $this->status == Absents::status_sakit;
    }

} 