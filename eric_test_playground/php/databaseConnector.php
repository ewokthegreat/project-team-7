<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/8/2016
 * Time: 3:38 PM
 */
class DatabaseConnector {

    function __construct() {
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

    public function insert($scan) {
        $props = $scan->getProperties();
        $tableName = $scan->getTableName();
        
        foreach($props as $field => $value) {
            $ins[] = ':' . $field;
        }
        
        $ins = implode(',', $ins);
        $fields = implode(',', array_keys($props));

        try {
            $sql = "INSERT INTO $tableName ($fields) VALUES ($ins)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($scan->getProperties());
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
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
        $allUsersArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'applicant');

        return $allUsersArray;
    }
    
    public function selectAllScansFromUser($applicantID) {
        $sql = "SELECT * FROM scan";
        $scanForUserArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'scan');
        
        return $scanForUserArray;
    }

    /**
     * @param $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }
}