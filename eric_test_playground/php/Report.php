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
    private $sortedByWeightFlaggedPostsArray;
    private $sortedByWeightFlaggedWordsAndFrequencyArray;

    /**
     * Report constructor.
     * @param $userID
     * @param $pathToReportData
     * @param $dateGenerated
     * @param $firstPostDate
     * @param $lastPostDate
     * @param $percentageOfFlaggedPosts
     * @param $averageWeightOfFlaggedPost
     * @param $favoriteTeam
     * @param $firstFlaggedPostDate
     * @param $lastFlaggedPostDate
     * @param $postsperYear
     * @param $sortedByWeightFlaggedPostsArray
     * @param $sortedByWeightFlaggedWordsAndFrequencyArray
     */
    public function __construct($userID, $pathToReportData, $dateGenerated, $firstPostDate, $lastPostDate, $percentageOfFlaggedPosts, $averageWeightOfFlaggedPost, $favoriteTeam, $firstFlaggedPostDate, $lastFlaggedPostDate, $flaggedPostsPerYear, $sortedByWeightFlaggedPostsArray, $sortedByWeightFlaggedWordsAndFrequencyArray)
    {
        $this->userID = $userID;
        $this->pathToReportData = $pathToReportData;
        $this->dateGenerated = $dateGenerated;
        $this->firstPostDate = $firstPostDate;
        $this->lastPostDate = $lastPostDate;
        $this->percentageOfFlaggedPosts = $percentageOfFlaggedPosts;
        $this->averageWeightOfFlaggedPost = $averageWeightOfFlaggedPost;
        $this->favoriteTeam = $favoriteTeam;
        $this->firstFlaggedPostDate = $firstFlaggedPostDate;
        $this->lastFlaggedPostDate = $lastFlaggedPostDate;
        $this->flaggedPostsPerYear = $flaggedPostsPerYear;
        $this->sortedByWeightFlaggedPostsArray = $sortedByWeightFlaggedPostsArray;
        $this->sortedByWeightFlaggedWordsAndFrequencyArray = $sortedByWeightFlaggedWordsAndFrequencyArray;
    }


    public function jsonSerialize() {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }
}