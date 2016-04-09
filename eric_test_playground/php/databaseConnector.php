<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/8/2016
 * Time: 3:38 PM
 */
class DatabaseConnector {

    function __construct() {
        echo "databaseConnector constructor initiated";
        
        $this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $this->opt);
    }

    private $host = "localhost";
    private $db = "ewoktheg_spider";
    private $user = "ewoktheg_spider";
    private $pass = "1qaz2wsx!QAZ@WSX";
    private $charset = "utf8";
    private $dsn;
    private $opt = [
        PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES      => false,
    ];
    private $pdo;
    private $query;

    /**
     * @param $user
     * @return PDOStatement
     */
    public function insertUser($user) {
        $sql = "INSERT INTO applicant(applicantID, fbAuthToken, firstName, lastName, email, profileLink, password, isAdmin, profilePicture)
                            VALUES(:id, :token, :fname, :lname, :email, :link, :pass, :isAdmin, :pic)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($user->getUserDataArray());
    }

    /**
     * @param $applicantID
     * @return mixed
     */
    public function selectUser($applicantID) {
        $sql = "SELECT * FROM applicant WHERE applicantID = :applicantID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['applicantID' => $applicantID]);
        $user = $stmt->fetch();
        
        return $user;
    }

    /**
     * @return array
     */
    public function selectAllUsers() {
        $sql = "SELECT * FROM applicant";
        $allUsersArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS, 'User');

        return $allUsersArray;
    }

    public function setQuery($query) {
        $this->query = $query;
    }
    


//new PDO("mysql:host=localhost;dbname=ewoktheg_spider;charset=utf8mb4", $user, $pass);
//"INSERT INTO applicant(applicantID, fb_auth_token, firstName, lastName, primaryEmail, profileLink, password)
//values("1", "123saasdqwea", "bob", "smith", "eric@ewokthegreat.com", "blah", "blah")");
//$stmt = $db->query("SELECT * from applicant");
//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($results);

}