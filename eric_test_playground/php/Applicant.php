<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/8/2016
 * Time: 5:55 PM
 * Description: This is a applicant object. This is essentially the person we are
 * scanning to see if they are a football person.
 */
class Applicant extends DBObject implements JsonSerializable {
    /**
     * User constructor.
     * @param $id
     * @param $token
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $link
     * @param $pass
     * @param $admin
     * @param $pic
     */
    public function __construct($id = null, $token = null, $firstName = null, $lastName = null,
                                $email = null, $link = null, $pass = null, $admin = null, $pic = null)
    {
        $this->applicantID = $id;
        $this->fbAuthToken = $token;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->profileLink = $link;
        $this->password = $pass;
        $this->isAdmin = $admin;
        $this->profilePicture = $pic;
    }

    protected $applicantID;
    protected $fbAuthToken;
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $profileLink;
    protected $password;
    protected $isAdmin;
    protected $profilePicture;

    /**
     * Specify data which should be serialized to JSON
     * @return array
     */
    public function jsonSerialize() {
        $props = array();

        foreach ($this as $key => $value) {
            if (!($key === 'password' ||
                $key === 'fbAuthToken' ||
                $key === 'isAdmin')
            ) {
                $props[$key] = $value;
            }
        }

        return $props;
    }

    /**
     * Return the name of the table in the database.
     * @return string
     */
    public function getTableName() {
        return 'applicant';
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param mixed $profilePicture
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicantID()
    {
        return $this->applicantID;
    }

    /**
     * @param mixed $applicantID
     * @return User
     */
    public function setApplicantID($applicantID)
    {
        $this->applicantID = $applicantID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFbAuthToken()
    {
        return $this->fbAuthToken;
    }

    /**
     * @param mixed $fbAuthToken
     * @return User
     */
    public function setFbAuthToken($fbAuthToken)
    {
        $this->fbAuthToken = $fbAuthToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfileLink()
    {
        return $this->profileLink;
    }

    /**
     * @param mixed $profileLink
     * @return User
     */
    public function setProfileLink($profileLink)
    {
        $this->profileLink = $profileLink;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param mixed $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }
}