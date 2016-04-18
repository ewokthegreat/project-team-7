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
    private $sortedByWeightFlaggedPostsArray;
    private $percentageOfFlaggedPosts;
   // private $totalWeightOfAllFlaggedPosts;
    private $sortedByWeightFlaggedWordsAndFrequencyArray;
    private $averageWeightOfFlaggedPost;
    private $favoriteTeam;

    /**
     * Report constructor.
     * @param $userID
     * @param $pathToReportData
     * @param $dateGenerated
     * @param $lastPostDate
     * @param $firstPostDate
     * @param $sortedByWeightFlaggedPostsArray
     * @param $percentageOfFlaggedPosts
     * @param $sortedByWeightFlaggedWordsAndFrequencyArray
     * @param $averageWeightOfFlaggedPost
     * @param $favoriteTeam
     */
    public function __construct($userID, $pathToReportData, $dateGenerated, $lastPostDate, $firstPostDate, $sortedByWeightFlaggedPostsArray, $percentageOfFlaggedPosts, $sortedByWeightFlaggedWordsAndFrequencyArray, $averageWeightOfFlaggedPost, $favoriteTeam)
    {
        $this->userID = $userID;
        $this->pathToReportData = $pathToReportData;
        $this->dateGenerated = $dateGenerated;
        $this->lastPostDate = $lastPostDate;
        $this->firstPostDate = $firstPostDate;
        $this->sortedByWeightFlaggedPostsArray = $sortedByWeightFlaggedPostsArray;
        $this->percentageOfFlaggedPosts = $percentageOfFlaggedPosts;
        $this->sortedByWeightFlaggedWordsAndFrequencyArray = $sortedByWeightFlaggedWordsAndFrequencyArray;
        $this->averageWeightOfFlaggedPost = $averageWeightOfFlaggedPost;
        $this->favoriteTeam = $favoriteTeam;
    }

    public function jsonSerialize() {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }
}