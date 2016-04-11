<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/8/2016
 * Time: 5:55 PM
 */

/**
 * Class User
 * This class represent's a user in the system. There are two type of user's, either an admin or applicant.
 */
class User
{
    //Private data members
    private $applicantID;
    private $fbAuthToken; //Facebook authorization token; used to access information on applicant's profile
    private $firstName; //User's first name
    private $lastName; //User's last name
    private $email; //User's email
    private $profileLink; //User's link to there profile
    private $password; //User's password for our local application only. This is not the Facebook profile
    private $isAdmin; //Specifies if a user is an admin or not
    private $profilePicture; //This is a link to the user's profile picture

    /**
     * User constructor.
     * @param $applicantID
     * @param $fbAuthToken
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $profileLink
     * @param $password
     * @param $isAdmin
     * @param $profilePicture
     */
    public function __construct($applicantID, $fbAuthToken, $firstName, $lastName, $email, $profileLink, $password, $isAdmin, $profilePicture)
    {
//        $this->applicantID = $applicantID;
//        $this->fbAuthToken = $fbAuthToken;
//        $this->firstName = $firstName;
//        $this->lastName = $lastName;
//        $this->email = $email;
//        $this->profileLink = $profileLin;
//        $this->password = $password;
//        $this->isAdmin = $isAdmin;
//        $this->profilePicture = $profilePicture;
        $this->setApplicantID($applicantID);
        $this->setFbAuthToken($fbAuthToken);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setEmail($email);
        $this->setProfileLink($profileLink);
        $this->setPassword($password);
        $this->setIsAdmin($isAdmin);
        $this->setProfileLink($profilePicture);
    }

//    private $applicantID;
//    private $fbAuthToken;
//    private $firstName;
//    private $lastName;
//    private $email;
//    private $profileLink;
//    private $password;
//    private $isAdmin;
//    private $profilePicture;

    /**
     * Get function for the user's data array
     * @return an array representing all the specified user's data. The array represents a list of
     * key => value pairs.
     */
    public function getUserDataArray() {
        $userDataArray = [
            'id' => $this->applicantID,
            'token' => $this->fbAuthToken,
            'fname' => $this->firstName,
            'lname' => $this->lastName,
            'email' => $this->email,
            'link' => $this->profileLink,
            'pass' => $this->password,
            'isAdmin' => $this->isAdmin,
            'pic' => $this->profilePicture
        ];
        
        return $userDataArray;
    }
    /**
     * Get function for user's profile picture
     * @return a link (string) to the user's specified profile picture
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set function for user's profile picture
     * @param $profilePicture a link (string) to the user's profile picture
     * @return User the instance of the user class
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
         return $this;
    }

    /**
     * Get function for applicant's ID
     * @return a string representing the applicant's ID
     */
    public function getApplicantID()
    {
        return $this->applicantID;
    }

    /**
     * Set method for applicant's ID
     * @param $applicantID a string representing the applicant's ID
     * @return User the instance of the user class
     */
    public function setApplicantID($applicantID)
    {
        $this->applicantID = $applicantID;
        return $this;
    }

    /**
     * Get method for user's Facebook authorization token
     * @return the user's Facebook authorization token as a string
     */
    public function getFbAuthToken()
    {
        return $this->fbAuthToken;
    }

    /**
     * Set method for user's Facebook authorization token
     * @param $fbAuthToken the user's Facebook authorization token as a string
     * @return User the instance of the user class
     */
    public function setFbAuthToken($fbAuthToken)
    {
        $this->fbAuthToken = $fbAuthToken;
        return $this;
    }

    /**
     * Get method for user's first name
     * @return firstName the user's first name as a string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set method for user's first name
     * @param $firstName the user's first name as a string
     * @return User the instance of the user class
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get method for user's last name
     * @return $lastName the user's last name a string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set method for user'ls last name
     * @param $lastName the user's last name a string
     * @return User the instance of the user class
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get method for user's email address
     * @return $email the user's email address as a string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set method for user's email address
     * @param $email the user's email address as a string
     * @return User the instance of the user class
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get method for user's profile link
     * @return $profileLink the user's profile link a string
     */
    public function getProfileLink()
    {
        return $this->profileLink;
    }

    /**
     * Set method for user's profile link
     * @param $profileLink user's profile link a string
     * @return User the instance of the user class
     */
    public function setProfileLink($profileLink)
    {
        $this->profileLink = $profileLink;
        return $this;
    }

    /**
     * Get method for user's password
     * @return user's password a string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set method for user's password
     * @param $password user's password as a string
     * @return User the instance of the user class
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get method to determine if the user is an admin
     * @return true if the user is an admin, else returns false
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set method to determine if a user is an admin
     * @param $isAdmin boolean value, either true or false
     * @return User the instance of the user class
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }
}