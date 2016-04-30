<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/29/2016
 * Time: 8:22 PM
 */

require_once "../php/Applicant.php";
require_once "phpunit-5.3.1.phar";

class TestProcessFBPosts extends PHPUnit_Framework_TestCase {

    public function testGetTableName() {
        $applicant = new Applicant();
        $expected = 'applicant';
        $actual = $applicant->getTableName();

        assertEquals($expected, $actual);
    }



//    $applicantObject;
//
//    public function setUp() {
//        //empty for now;
//    }

}


?>