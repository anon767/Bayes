<?php
/**
 * @project: Bayes
 * @author: Tom
 * @date: 03.01.2017
 */
require("Feature.php");

class Bayes
{
    var $features = [];
    var $naming = array();
    var $messages = 0;

    /**
     * Split sentence by every word
     * @param $sentence
     * @return array
     */
    public function splitByRegEx($sentence)
    {
        return preg_split("/\\s+|\\b(?=[!\\?\\.])(?!\\.\\s+)/", $sentence);
    }


    /**
     * Learn by labeled Sentence
     * @param $sentence
     * @param $class
     */
    public function learn($sentence, $class)
    {
        $words = $this->splitByRegEx($sentence);
        if (!array_key_exists($class, $this->features)) {
            $this->features[$class] = new Feature();
            $this->features[$class]->setName($class);
        }
        $this->messages++;
        $this->features[$class]->setMessageCount($this->features[$class]->getMessageCount() + 1);
        foreach ($words as $word) {
            if ($word != "") {
                $word = mb_strtolower($word);
                $this->features[$class]->addWord($word);
            }
        }
    }

    /**
     * @param $sentence
     * @return false|int|string
     */
    public function classify($sentence)
    {

        $words = $this->splitByRegEx($sentence);
        $max = [];
        foreach ($this->features as $feature) {
            $probability = $feature->calcProbability($this->messages);
            foreach ($words as $word) {
                $amountWords = (array_key_exists($word, $feature->getWords()) && $feature->getWord($word) > 0) ? $feature->getWord($word) : 0.2;
                $probability *= ($amountWords / $feature->getMessageCount());
            }
            $max[$feature->getName()] = log($probability);
        }
        return array_search(max($max), $max);
    }
}