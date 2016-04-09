<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/8/2016
 * Time: 5:55 PM
 */
class User
{
    private $applicantID;
    private $fbAuthToken;
    private $firstName;
    private $lastName;
    private $email;
    private $profileLink;
    private $password;
    private $isAdmin;

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