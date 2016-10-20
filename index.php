<?php
/**
 * Created by PhpStorm.
 * User: tg
 * Date: 27.09.2016
 * Time: 08:24
 * Spam/Ham mails taken from http://archive.ics.uci.edu/ml/datasets/SMS+Spam+Collection
 */
include("Bayes.php");
$bayes = new Bayes(["Spam","Ham"]);

$testContents = explode("\n",file_get_contents("testfiles/SMSSpamCollection.txt"));
foreach($testContents as $testCase){
    if(strstr($testCase,"ham")){
        $testCase = str_replace("ham","",$testCase);
        $bayes->learn($bayes->splitByRegEx($testCase),BAYES::GOOD);
    }else{
        $testCase = str_replace("spam","",$testCase);
        $bayes->learn($bayes->splitByRegEx($testCase),BAYES::BAD);
    }
}

$evaluating = "URGENT! Your Mobile No 07808726822 was awarded a L2,000 Bonus Caller Prize on 02/09/03! This is our 2nd attempt to contact YOU! Call 0871-872-9758 BOX95QU ";
echo $bayes->classify($bayes->splitByRegEx($evaluating));
echo "\r\n<br>\r\n";
$evaluating = "Hmm...my uncle just informed me that he's paying the school directly. So pls buy food";
echo $bayes->classify($bayes->splitByRegEx($evaluating));

