<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 3:21 PM
 */
class Report
{
    private $scanID;
    private $applicantID;
    private $score;
    private $date;
    private $path;

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