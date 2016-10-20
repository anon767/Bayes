<?php
error_reporting(E_ALL ^ E_NOTICE);

/**
 * Created by PhpStorm.
 * User: tg
 * Date: 26.09.2016
 * Time: 14:17
 */
class Bayes
{
    const BAD = 0;
    const GOOD = 1;
    var $files = [0, 0];
    var $naming = array();
    var $wordsBad = array();
    var $wordsGood = array();

    public function __construct($naming)
    {
        $this->naming = $naming;
    }

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
     * P(BAD) = Amount BAD / Amount of files
     * @return float|int
     */
    private function calcProbabilityMalware()
    {
        return ($this->files[SELF::BAD]) / ($this->files[SELF::BAD] + $this->files[SELF::GOOD]);
    }

    /**
     * P(GOOD) = Amount GOOD / Amount of Files
     * @return float|int
     */
    private function calcProbabilityNonMalware()
    {
        return ($this->files[SELF::GOOD]) / ($this->files[SELF::BAD] + $this->files[SELF::GOOD]);
    }


    /**
     * adds +1 to either BAD or GOOD
     * foreach word in sentence -> increment word occurence in either wordsBad or wordsGood
     * @param $words
     * @param $class
     */
    public function learn($words, $class) // $class = malware/nonmalware
    {
        $this->files[$class] += 1;
        foreach ($words as $word) {
            if ($word != "") {
                $word = mb_strtolower($word);
                if ($class == SELF::BAD) {
                    $this->wordsBad[$word] = $this->wordsBad[$word] + 1;
                } else {
                    $this->wordsGood[$word] = $this->wordsGood[$word] + 1;
                }
            }
        }
    }

    /**
     * ( P(W1|Malware)*....P(Wn|Malware)/P(W1|NonMalware)*...P(Wn|NonMalware) ) * P(Malware)/P(NonMalware)
     * q > 1 := malware
     * q <= 1 := non Malware
     * @param $words
     * @return string
     */
    public function classify($words)
    {
        $badprobability = $this->calcProbabilityMalware();
        $goodprobability = $this->calcProbabilityNonMalware();
        foreach ($words as $word) {
            $amountWordBad = $this->wordsBad[$word] > 0 ? $this->wordsBad[$word] : 1;
            $amountWordGood = $this->wordsGood[$word] > 0 ? $this->wordsGood[$word] : 1;
            $badprobability *= $amountWordBad / (count($this->wordsBad));
            $goodprobability *= $amountWordGood / (count($this->wordsGood));
        }
        $q = $badprobability / $goodprobability;
        if ($q > 1) {
            return $this->naming[SELF::BAD];
        } else {
            return $this->naming[SELF::GOOD];
        }
    }
}