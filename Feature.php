<?php
/**
 * @project: Bayes
 * @author: Tom
 * @date: 03.01.2017
 */




class Feature
{
    private $messageCount = 0;
    private $name = "";
    private $words = [];

    /**
     * @return int
     */
    public function getMessageCount()
    {
        return $this->messageCount;
    }

    /**
     * @param int $messageCount
     */
    public function setMessageCount($messageCount)
    {
        $this->messageCount = $messageCount;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getWords()
    {
        return $this->words;
    }

    public function getWord($word)
    {
        return $this->words[$word];
    }

    /**
     * @param array $words
     */
    public function setWords($words)
    {
        $this->words = $words;
    }

    public function addWord($word)
    {
        $this->words[$word] = (array_key_exists($word, $this->words) ? $this->words[$word] : 0) + 1;
    }

    /**
     * @param $messages
     * @return float|int
     */
    public function calcProbability($messages)
    {
        return $this->getMessageCount() / ($messages);
    }
}