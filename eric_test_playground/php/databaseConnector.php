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

    public function insert($obj) {
        if($obj instanceOf DBObject) {
            $props = $obj->getProperties();
            $tableName = $obj->getTableName();

            foreach ($props as $field => $value) {
                $ins[] = ':' . $field;
            }

            $ins = implode(',', $ins);
            $fields = implode(',', array_keys($props));

            try {
                $sql = "INSERT INTO $tableName ($fields) VALUES ($ins)";

                echo 'This is where the error is:';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($obj->getProperties());

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            throw new Exception('Parameter must be of type DBObject');
        }
    }

    /**
     * @param $applicantID
     * @return mixed
     */
    public function selectApplicant($applicantID) {
        $sql = "SELECT * FROM applicant WHERE applicantID = :applicantID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['applicantID' => $applicantID]);
        $applicant = $stmt->fetch();

        return $applicant;
    }

    /**
     * @return array
     */
    public function selectAllApplicants() {

        $sql = "SELECT * FROM applicant WHERE applicantID != 999";

        $allApplicantsArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'applicant');

        return $allApplicantsArray;
    }

    public function selectAllScansFromApplicant($applicantID) {
        $sql = "SELECT * FROM scan WHERE applicantID = $applicantID";
        $scanForApplicantArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'scan');

        return $scanForApplicantArray;
    }

    public function createAllUserSummaryObject() {
        $sql = "SELECT * FROM applicant JOIN scan ON applicant.applicantID = scan.applicantID";
        $userSummaryObject = $this->pdo->query($sql)->fetchAll();

        return $userSummaryObject;
    }

    /**
     * @param $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }
}