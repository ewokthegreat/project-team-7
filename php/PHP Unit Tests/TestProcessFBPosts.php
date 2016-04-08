<?php
require_once(__DIR__ . '/phpunit-5.3.1.phar');
require_once '../ProcessFBPosts.php';
require_once '../WordBank.php';

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 4/7/2016
 * Time: 6:47 PM
 */
class TestProcessFBPosts extends PHPUnit_Framework_TestCase {

    public function setUp() {
        //empty for now;
    }

    /**
     * Tests if all words are received from the word bank.
     */
    public function testGetAllWordBanks() {
        // Call the function
        $word_bank_list = getAllWordBanks();

        // Get total counts from word list
        $count = 0;

        // Using this assignment operator basically added the enter file into an array. Would need to apply to all
        // 5 wordbanks.
        $f1 = file("../SpiderWordBank.csv");
        $count +=  count($f1);

        // Keep doing the same thing for all word banks.
        $f2 = file("../nfl_stop_players_2015csv.csv");
        $count += count($f2);

        $f2 = file("../nfl_stop_players_2015csv.csv");
        $count += count($f2);

        $f3 = file("../nfl_city_state.csv");
        $count += count($f3);

        $f4 = file("../nfl_stadiums.csv");
        $count += count($f4;

        $f5 = file("../nfl_team_names.csv");
        $count += count($f5);

        //Checks to see if the total elements in the list are the same. If they are, then this function
        //successfully retrieved all the word
        $this->assertTrue($count == count($word_bank_list));
    }

    public function getAllPosts() {
        //since this is mainly using Facebook's API, I am not sure how to test this.
        //I will pass on this for now
    }

    public function testFlagPosts() {
        //this looks like a work in progress, and can be broken down into smaller methods.
        //will not modify for now.
    }
}


?>