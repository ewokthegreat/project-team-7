<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 8:07 PM
 */

class AppEngine
{
    /**
     * AppEngine constructor.
     * @param $wordBank
     */
    public function __construct($wordBank) {
        trace("AppEngine Initialized");
        $this->wordBank = $wordBank;
        $this->db = new DatabaseConnector();
        $this->reportGenerator = new ReportGenerator();
        $this->reportGenerator->setDictionaryData($wordBank);
        $this->fb = $this->createServiceClass();
        $this->token = $this->getAccessToken();
        $this->user = $this->generateUserData();

    }

    public function init() {
        $this->queryTimeline();
        $report = $this->getReportGenerator()->init();
        $this->saveAceData($report);
    }

    private function saveAceData($data) {
        $applicantID = $data->getUserID();
        $scanDate = $data->getDateGenerated();
        $dirPath = __USER_DATA__ . '/' . $applicantID;
        $filename = $scanDate . '__' . $applicantID;
        $extension = '.ace';
        $fullPath = $dirPath . '/' . $filename . $extension;

        $this->writeFile($fullPath, $data);

        $scan = new Scan($filename, $applicantID, '99', $scanDate, $fullPath);
        $this->getDb()->insert($scan);
    }
    /**
     * @param $requestString
     * @param $callback
     */
    public function makeGraphRequest($requestString, $callback) {
        $fb = $this->fb;

        try {
            $response = $fb->get($requestString);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $callback($response);
    }

    private $fb;
    private $id = '1679655878969496';
    private $secret = '74ab0d53fbe6e26d3f001bc7f31cfcea';
    private $version = 'v2.5';
    private $token;
    private $user;
    private $db;
    private $reportGenerator;
    private $wordBank;
    private $scan;

    /**
     * @return ReportGenerator
     */
    public function getReportGenerator()
    {
        return $this->reportGenerator;
    }

    /**
     * @param ReportGenerator $reportGenerator
     * @return AppEngine
     */
    public function setReportGenerator($reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getRawPostData()
    {
        return $this->rawPostData;
    }

    /**
     * @param mixed $rawPostData
     * @return AppEngine
     */
    public function setRawPostData($rawPostData)
    {
        $this->rawPostData = $rawPostData;
        return $this;
    }
    /**
     * @return \Facebook\Facebook
     */
    public function getFb()
    {
        return $this->fb;
    }

    /**
     * @param \Facebook\Facebook $fb
     */
    public function setFb($fb)
    {
        $this->fb = $fb;
    }

    /**
     * @return DatabaseConnector
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DatabaseConnector $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return \Facebook\Facebook
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param \Facebook\Facebook $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Facebook\Facebook
     */
    private function createServiceClass() {
        $fb = new \Facebook\Facebook([
            'app_id' => $this->id,
            'app_secret' => $this->secret,
            'default_graph_version' => $this->version
        ]);

        return $fb;
    }

    /**
     * @param $fb - an authorized instance of our application
     * @return mixed - an object containing the user data we need
     */
    private function generateUserData() {
        $db = $this->db;

        $requestString = '/me?fields=id,first_name,last_name,email,link,picture.width(80)';
        $this->makeGraphRequest($requestString, function($response) use($db) {
            $user = json_decode($response->getGraphUser());
            $applicantID = $user->id;
            $fbAuthToken = $this->fb->getDefaultAccessToken()->getValue();
            $firstName = $user->first_name;
            $lastName = $user->last_name;
            $email = $user->email;
            $profileLink = $user->link;
            $password = '1qaz2wsx!QAZ@WSX';
            $isAdmin = FALSE;
            $profilePicture = $user->picture->url;
            $userData = new Applicant($applicantID, $fbAuthToken, $firstName, $lastName,
                $email, $profileLink, $password, $isAdmin, $profilePicture);
            $this->getReportGenerator()->setUserId($applicantID);
            $db->insert($userData);
        });
    }
    private function writeFile($path, $data) {
        if(file_put_contents($path, json_encode($data))) {
            $this->getReportGenerator()->setPathToData($path);
            trace('File operation successful.');
        } else {
            trace('A file with that name already exists!');
        };

    }

    /**
     * @param $data
     */
    private function writeGraphResponseToDiskAsJSON($data) {
        $this->makeGraphRequest('/me?fields=id', function($response) use($data) {
            $user = json_decode($response->getGraphUser());
            $userID = $user->id;

            $dateString = date('y.m.d.G.i.s__');
            $pathToUserData = __USER_DATA__ . '/' . $userID;
            $userDataFileName = $dateString . $userID . '.json';
            $path = $pathToUserData . '/' . $userDataFileName;

            if(!file_exists($pathToUserData)) {
                mkdir($pathToUserData);
                $this->writeFile($path, $data);
            } else {
                echo "'\n' Directory already exists, attempting to write file! '\n'";
                $this->writeFile($path, $data);
            }
        });
    }

    /**
     *
     */
    private function queryTimeline() {
        $this->makeGraphRequest('/me/posts?limit=100', function($response) {
            $fb = $this->getFb();
            $posts_response = $response->getGraphEdge();
            $rawPostData = array();

            if ($fb->next($posts_response)) {
                $response_array = $posts_response->asArray();
                $rawPostData = array_merge($rawPostData, $response_array);

                while ($posts_response = $fb->next($posts_response)) {
                    $response_array = $posts_response->asArray();
                    $rawPostData = array_merge($rawPostData, $response_array);
                }

            } else {
                $posts_response = $response->getGraphEdge()->asArray();
                array_push($rawPostData, $posts_response);
            }

            self::writeGraphResponseToDiskAsJSON($rawPostData);
        });
    }

    /**
     * @return \Facebook\Facebook
     */
    private function getAccessToken() {
        $fb = $this->fb;
        $helper = $fb->getJavaScriptHelper();

        // checking if access token is not null
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            if (isset($_SESSION['facebook_access_token'])) {
                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            } else {
                $_SESSION['facebook_access_token'] = (string)$accessToken;
                // OAuth 2.0 client handler
                $oAuth2Client = $fb->getOAuth2Client();
                // Exchanges a short-lived access token for a long-lived one
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            }
        }

        return $_SESSION['facebook_access_token'];
    }

}