<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/12/2016
 * Time: 2:02 AM
 */
class PostData implements JsonSerializable{

    private $id;
    private $date;
    private $message;
    private $story;

    /**
     * PostData constructor.
     * @param $obj
     */
    public function __construct($obj) {
        $this->setId($obj->id);
        $this->setDate($obj->created_time->date);

        if(isset($obj->message)) {
            $this->setMessage($obj->message);
        }

        if(isset($obj->story)) {
            $this->setStory($obj->story);
        }
    }

    public function jsonSerialize() {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }

    /**
     * @param $delimiter
     * @return array
     */
    public function getWordArray($delimiter = '/\W/') {
        $story = $this->getStory();
        $message = $this->getMessage();
        $postText = $story . $message;

        return preg_split($delimiter, $postText, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function getAllWordsAsString() {
        return $this->getStory() . ' ' . $this->getMessage();
    }
    
    /**
     * @return mixed
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * @param mixed $story
     * @return PostData
     */
    public function setStory($story)
    {
        $this->story = $story;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Post
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
}