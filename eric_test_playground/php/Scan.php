<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 3:21 PM
 * Details: This class represents a scan and stores basically all the applicant's details
 * as well as their raw post data.
 */
class Scan extends DBObject implements JsonSerializable {
    /**
     * Scan constructor.
     * @param $scanID
     * @param $applicantID
     * @param $score
     * @param $date
     * @param $path
     */
    public function __construct($scanID = null, $applicantID = null,
                                $score = null, $date = null, $path = null) {
        $this->scanID = $scanID;
        $this->applicantID = $applicantID;
        $this->score = $score;
        $this->date = $date;
        $this->path = $path;
    }

    protected $scanID;
    protected $applicantID;
    protected $score;
    protected $date;
    protected $path;

    /**
     * Used to help convert this class into JSON file.
     * @return array
     */
    public function jsonSerialize() {
        $props = array();

        foreach ($this as $key => $value) {
            if(!($key === 'applicantID')) {
                $props[$key] = $value;
            }
        }

        return $props;
    }

    public function getTableName() {
        return 'scan';
    }
    
    /**
     * @return mixed
     */
    public function getScanID()
    {
        return $this->scanID;
    }

    /**
     * @param mixed $scanID
     * @return report
     */
    public function setScanID($scanID)
    {
        $this->scanID = $scanID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicantID()
    {
        return $this->applicantID;
    }

    /**
     * @param mixed $applicantID
     * @return report
     */
    public function setApplicantID($applicantID)
    {
        $this->applicantID = $applicantID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     * @return report
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return report
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return report
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}