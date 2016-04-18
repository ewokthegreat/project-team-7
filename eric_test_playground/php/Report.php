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
    private $sortedByWeightFlaggedPostsArray;
    private $sortedByWeightFlaggedWordsAndFrequencyArray;

    /**
     * Report constructor.
     * @param $userID
     * @param $dateGenerated
     * @param $pathToReportData
     * @param $firstPostDate
     * @param $lastPostDate
     * @param $percentageOfFlaggedPosts
     * @param $averageWeightOfFlaggedPost
     * @param $favoriteTeam
     * @param $sortedByWeightFlaggedPostsArray
     * @param $sortedByWeightFlaggedWordsAndFrequencyArray
     */
    public function __construct($userID, $dateGenerated, $pathToReportData, $firstPostDate, $lastPostDate, $percentageOfFlaggedPosts, $averageWeightOfFlaggedPost, $favoriteTeam, $sortedByWeightFlaggedPostsArray, $sortedByWeightFlaggedWordsAndFrequencyArray)
    {
        $this->userID = $userID;
        $this->dateGenerated = $dateGenerated;
        $this->pathToReportData = $pathToReportData;
        $this->firstPostDate = $firstPostDate;
        $this->lastPostDate = $lastPostDate;
        $this->percentageOfFlaggedPosts = $percentageOfFlaggedPosts;
        $this->averageWeightOfFlaggedPost = $averageWeightOfFlaggedPost;
        $this->favoriteTeam = $favoriteTeam;
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