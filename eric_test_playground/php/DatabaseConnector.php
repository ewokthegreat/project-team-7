<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/8/2016
 * Time: 3:38 PM
 */
class DatabaseConnector {

    /**
     * DatabaseConnector constructor.
     */
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

    /**
     * Does an insert on the specified object.
     * @param $obj Scan/Applicant being inserted into the table.
     * @throws Exception
     */
    public function insert($obj) {
        if($obj instanceOf DBObject) {
            $props = $obj->getProperties();
            $tableName = $obj->getTableName();

            foreach ($props as $field => $value) {
                $ins[] = ':' . $field;
            }

            $ins_backup = $ins;
            $primary_column = $ins[0];

            $ins = implode(',', $ins);
            $fields = implode(',', array_keys($props));

            try {
                //Check to see if insert is an applicant's table
                if ($primary_column == ':applicantID') {
                    $applicantID_value = $props["applicantID"];
                    $allApplicants = $this->selectAllApplicants();

                    foreach($allApplicants as $applicant) { //iterate through all applicant's to see if there is a match
                        if($applicant->getApplicantID() == $applicantID_value) { //a match was found
                            $success_update = $this->updateApplicantTable($tableName, $ins_backup, $props);
                            if ($success_update) { //if inserted, can terminate early
                                return;
                            } else {
                                throw new PDOException("Update failed for applicant table.");
                            }
                        }
                    }
                }

                //If code reaches here, this does a regular insert. This occurs either
                //because there is a new applicant or a scan is being inserted into the DB.
                $sql = "INSERT INTO $tableName ($fields) VALUES ($ins)";

                $stmt = $this->pdo->prepare($sql);
                $success = $stmt->execute($obj->getProperties());

                trace("Success: " . $success);
            } catch (PDOException $e) {
                echo 'This is where the error is:';
                echo $e->getMessage();
            }
        } else {
            throw new Exception('Parameter must be of type DBObject');
        }
    }

    /**
     * Gets an applicant from the database by there applicant ID.
     * @param $applicantID string, primary key value that will be used to
     * get applicant from table.
     * @return mixed in most cases returns an Applicant object.
     */
    public function selectApplicant($applicantID) {
        $sql = "SELECT * FROM applicant WHERE applicantID = :applicantID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['applicantID' => $applicantID]);
        $applicant = $stmt->fetch();

        return $applicant;
    }

    /**
     * Gets all applicants that are current in data base.
     * @return array of all instances of Applicants.
     */
    public function selectAllApplicants() {

        $sql = "SELECT * FROM applicant WHERE applicantID != 999";

        $allApplicantsArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'applicant');

        return $allApplicantsArray;
    }

    /**
     * Gets all the scans from a particular applicant.
     * @param $applicantID string foreign key value that will be used to get all scans.
     * @return array of scan objects pertaining to one applicant.
     */
    public function selectAllScansFromApplicant($applicantID) {
        $sql = "SELECT * FROM scan WHERE applicantID = $applicantID";
        $scanForApplicantArray = $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'scan');

        return $scanForApplicantArray;
    }

    /**
     * Returns a summary of all applicants with scans.
     * @return array of summary object.
     */
    public function createAllUserSummaryObject() {
        $sql = "SELECT * FROM applicant JOIN scan ON applicant.applicantID = scan.applicantID";
        $userSummaryObject = $this->pdo->query($sql)->fetchAll();

        return $userSummaryObject;
    }

    /**
     * This method does an update on applicant table in case an applicant already exists
     * in the database.
     * @param $tableName that will be updated
     * @param $fieldPlaceholders field names used to update the database
     * @param $props contains all the finromation to the database
     * @return bool True if the update was successful, otherwise returns false.
     */
    public function updateApplicantTable($tableName, $fieldPlaceholders, $props)
    {
        //there is a match, now do an update instead of insert
        $update_sql = "UPDATE $tableName SET fbAuthToken = $fieldPlaceholders[1],
                                                                 firstName = $fieldPlaceholders[2],
                                                                 lastName = $fieldPlaceholders[3],
                                                                 email = $fieldPlaceholders[4],
                                                                 profileLink = $fieldPlaceholders[5],
                                                                 password = $fieldPlaceholders[6],
                                                                 isAdmin = $fieldPlaceholders[7],
                                                                 profilePicture = $fieldPlaceholders[8] WHERE applicantID = $fieldPlaceholders[0]";

        $stmt = $this->pdo->prepare($update_sql);
        $stmt->bindValue(":fbAuthToken", $props["fbAuthToken"]);
        $stmt->bindValue(":firstName", $props["firstName"]);
        $stmt->bindValue(":lastName", $props["lastName"]);
        $stmt->bindValue(":email", $props["email"]);
        $stmt->bindValue(":profileLink", $props["profileLink"]);
        $stmt->bindValue(":password", $props["password"]);
        $stmt->bindValue(":isAdmin", $props["isAdmin"]);
        $stmt->bindValue(":profilePicture", $props["profilePicture"]);
        $stmt->bindValue(":applicantID", $props["applicantID"]);

        $success_update = $stmt->execute();
        trace("Success: " . $success_update);

        return $success_update;
    }

    /**
     * @param $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return DatabaseConnector
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param PDO $pdo
     * @return DatabaseConnector
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
        return $this;
    }

    /**
     * @return array
     */
    public function getOpt()
    {
        return $this->opt;
    }

    /**
     * @param array $opt
     * @return DatabaseConnector
     */
    public function setOpt($opt)
    {
        $this->opt = $opt;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     * @return DatabaseConnector
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @param string $dsn
     * @return DatabaseConnector
     */
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
        return $this;
    }

    /**
     * @return string
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param string $db
     * @return DatabaseConnector
     */
    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return DatabaseConnector
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     * @return DatabaseConnector
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }
}