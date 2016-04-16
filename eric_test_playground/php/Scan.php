<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 3:21 PM
 */
class Scan extends DBObject{
    public function __construct($scanId, $applicantId, $score, $date, $path) {
        $this->setScanID($scanId);
        $this->setApplicantID($applicantId);
        $this->setScore($score);
        $this->setDate($date);
        $this->setPath($path);
    }

    /*
        $this->setScanID(basename($fileName, '.json'));
        $fileInfo = explode('__', $this->getScanID());
        $this->setApplicantID($fileInfo[1]);
        $this->setDate($fileInfo[0]);

        $path = $this->getScanID() . 'ace';

        $this->setPath($path);

        // Unused field (for now)
        $this->setScore(NULL);
     */
    private $scanID;
    private $applicantID;
    private $score;
    private $date;
    private $path;

    public function getTableName() {
        return 'scan';
    }

    public function getDataArray() {
        $scanDataArray = [
            'scanID' => $this->getScanID(),
            'applicantID' => $this->getApplicantID(),
            'score' => $this->getScore(),
            'timestamp' => $this->getDate(),
            'path' => $this->getPath()
        ];
        
        return $scanDataArray;
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