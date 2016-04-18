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
    private $totalWeightOfAllFlaggedPosts;
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
     * @param $totalWeightOfAllFlaggedPosts
     * @param $sortedByWeightFlaggedWordsAndFrequencyArray
     * @param $averageWeightOfFlaggedPost
     * @param $favoriteTeam
     */
    public function __construct($userID, $pathToReportData, $dateGenerated, $lastPostDate, $firstPostDate, $sortedByWeightFlaggedPostsArray, $percentageOfFlaggedPosts, $totalWeightOfAllFlaggedPosts, $sortedByWeightFlaggedWordsAndFrequencyArray, $averageWeightOfFlaggedPost, $favoriteTeam)
    {
        $this->userID = $userID;
        $this->pathToReportData = $pathToReportData;
        $this->dateGenerated = $dateGenerated;
        $this->lastPostDate = $lastPostDate;
        $this->firstPostDate = $firstPostDate;
        $this->sortedByWeightFlaggedPostsArray = $sortedByWeightFlaggedPostsArray;
        $this->percentageOfFlaggedPosts = $percentageOfFlaggedPosts;
        $this->totalWeightOfAllFlaggedPosts = $totalWeightOfAllFlaggedPosts;
        $this->sortedByWeightFlaggedWordsAndFrequencyArray = $sortedByWeightFlaggedWordsAndFrequencyArray;
        $this->averageWeightOfFlaggedPost = $averageWeightOfFlaggedPost;
        $this->favoriteTeam = $favoriteTeam;
    }

    public function jsonSerialize() {
        $props = array();
<<<<<<< HEAD

        foreach ($this as $key => $value) {
=======
        foreach($this as $key => $value) {
>>>>>>> ad4e4753da5b91d43b8c16e0def2ea79e477a796
            $props[$key] = $value;
        }

        return $props;
    }
}