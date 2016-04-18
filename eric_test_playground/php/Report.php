<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/17/2016
 * Time: 9:14 PM
 */
class Report implements jsonSerializable {
    private $userID;
    private $dateGenerated;
    private $firstPostDate;
    private $lastPostDate;
    private $flaggedPostsArray;
    private $pathToReportData;

    public function jsonSerialize() {
        // TODO: Implement jsonSerialize() method.
    }
}