<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/17/2016
 * Time: 9:14 PM
 */
class Report implements jsonSerializable {
    private $userID;
    private $pathToReportData;
    private $dateGenerated;
    private $firstPostDate;
    private $lastPostDate;
    private $percentageOfFlaggedPosts;
    private $averageWeightOfFlaggedPost;
    private $favoriteTeam;
    private $firstFlaggedPostDate;
    private $lastFlaggedPostDate;
    private $flaggedPostsPerYear;
    private $bubbleGraphData;
    private $sortedByWeightFlaggedPostsArray;
    private $sortedByWeightFlaggedWordsAndFrequencyArray;
    private $intervalFlaggedPosts;

    /**
     * Report constructor.
     * @param $userID
     * @param $pathToReportData
     * @param $dateGenerated
     * @param $percentageOfFlaggedPosts
     * @param $averageWeightOfFlaggedPost
     * @param $favoriteTeam
     * @param $firstFlaggedPostDate
     * @param $lastFlaggedPostDate
     * @param $flaggedPostsPerYear
     * @param $bubbleGraphData
     * @param $sortedByWeightFlaggedPostsArray
     * @param $sortedByWeightFlaggedWordsAndFrequencyArray
     * @param $intervalFlaggedPosts
     */
    public function __construct($userID, $pathToReportData, $dateGenerated, $percentageOfFlaggedPosts, $averageWeightOfFlaggedPost, $favoriteTeam, $firstFlaggedPostDate, $lastFlaggedPostDate, $flaggedPostsPerYear, $bubbleGraphData, $sortedByWeightFlaggedPostsArray, $sortedByWeightFlaggedWordsAndFrequencyArray, $intervalFlaggedPosts)
    {
        $this->userID = $userID;
        $this->pathToReportData = $pathToReportData;
        $this->dateGenerated = $dateGenerated;
        $this->percentageOfFlaggedPosts = $percentageOfFlaggedPosts;
        $this->averageWeightOfFlaggedPost = $averageWeightOfFlaggedPost;
        $this->favoriteTeam = $favoriteTeam;
        $this->firstFlaggedPostDate = $firstFlaggedPostDate;
        $this->lastFlaggedPostDate = $lastFlaggedPostDate;
        $this->flaggedPostsPerYear = $flaggedPostsPerYear;
        $this->bubbleGraphData = $bubbleGraphData;
        $this->sortedByWeightFlaggedPostsArray = $sortedByWeightFlaggedPostsArray;
        $this->sortedByWeightFlaggedWordsAndFrequencyArray = $sortedByWeightFlaggedWordsAndFrequencyArray;
        $this->intervalFlaggedPosts = $intervalFlaggedPosts;
    }


    public function jsonSerialize() {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     * @return Report
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPathToReportData()
    {
        return $this->pathToReportData;
    }

    /**
     * @param mixed $pathToReportData
     * @return Report
     */
    public function setPathToReportData($pathToReportData)
    {
        $this->pathToReportData = $pathToReportData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateGenerated()
    {
        return $this->dateGenerated;
    }

    /**
     * @param mixed $dateGenerated
     * @return Report
     */
    public function setDateGenerated($dateGenerated)
    {
        $this->dateGenerated = $dateGenerated;
        return $this;
    }


    /**
     * @param mixed $firstPostDate
     * @return Report
     */
    public function setFirstPostDate($firstPostDate)
    {
        $this->firstPostDate = $firstPostDate;
        return $this;
    }



    /**
     * @param mixed $lastPostDate
     * @return Report
     */
    public function setLastPostDate($lastPostDate)
    {
        $this->lastPostDate = $lastPostDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercentageOfFlaggedPosts()
    {
        return $this->percentageOfFlaggedPosts;
    }

    /**
     * @param mixed $percentageOfFlaggedPosts
     * @return Report
     */
    public function setPercentageOfFlaggedPosts($percentageOfFlaggedPosts)
    {
        $this->percentageOfFlaggedPosts = $percentageOfFlaggedPosts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAverageWeightOfFlaggedPost()
    {
        return $this->averageWeightOfFlaggedPost;
    }

    /**
     * @param mixed $averageWeightOfFlaggedPost
     * @return Report
     */
    public function setAverageWeightOfFlaggedPost($averageWeightOfFlaggedPost)
    {
        $this->averageWeightOfFlaggedPost = $averageWeightOfFlaggedPost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFavoriteTeam()
    {
        return $this->favoriteTeam;
    }

    /**
     * @param mixed $favoriteTeam
     * @return Report
     */
    public function setFavoriteTeam($favoriteTeam)
    {
        $this->favoriteTeam = $favoriteTeam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstFlaggedPostDate()
    {
        return $this->firstFlaggedPostDate;
    }

    /**
     * @param mixed $firstFlaggedPostDate
     * @return Report
     */
    public function setFirstFlaggedPostDate($firstFlaggedPostDate)
    {
        $this->firstFlaggedPostDate = $firstFlaggedPostDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastFlaggedPostDate()
    {
        return $this->lastFlaggedPostDate;
    }

    /**
     * @param mixed $lastFlaggedPostDate
     * @return Report
     */
    public function setLastFlaggedPostDate($lastFlaggedPostDate)
    {
        $this->lastFlaggedPostDate = $lastFlaggedPostDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFlaggedPostsPerYear()
    {
        return $this->flaggedPostsPerYear;
    }

    /**
     * @param mixed $flaggedPostsPerYear
     * @return Report
     */
    public function setFlaggedPostsPerYear($flaggedPostsPerYear)
    {
        $this->flaggedPostsPerYear = $flaggedPostsPerYear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortedByWeightFlaggedPostsArray()
    {
        return $this->sortedByWeightFlaggedPostsArray;
    }

    /**
     * @param mixed $sortedByWeightFlaggedPostsArray
     * @return Report
     */
    public function setSortedByWeightFlaggedPostsArray($sortedByWeightFlaggedPostsArray)
    {
        $this->sortedByWeightFlaggedPostsArray = $sortedByWeightFlaggedPostsArray;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortedByWeightFlaggedWordsAndFrequencyArray()
    {
        return $this->sortedByWeightFlaggedWordsAndFrequencyArray;
    }

    /**
     * @param mixed $sortedByWeightFlaggedWordsAndFrequencyArray
     * @return Report
     */
    public function setSortedByWeightFlaggedWordsAndFrequencyArray($sortedByWeightFlaggedWordsAndFrequencyArray)
    {
        $this->sortedByWeightFlaggedWordsAndFrequencyArray = $sortedByWeightFlaggedWordsAndFrequencyArray;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBubbleGraphData()
    {
        return $this->bubbleGraphData;
    }

    /**
     * @param mixed $bubbleGraphData
     */
    public function setBubbleGraphData($bubbleGraphData)
    {
        $this->bubbleGraphData = $bubbleGraphData;
    }

    /**
     * @return mixed
     */
    public function getIntervalFlaggedPosts()
    {
        return $this->intervalFlaggedPosts;
    }

    /**
     * @param mixed $intervalFlaggedPosts
     */
    public function setIntervalFlaggedPosts($intervalFlaggedPosts)
    {
        $this->intervalFlaggedPosts = $intervalFlaggedPosts;
    }


}