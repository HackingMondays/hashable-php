<?php
$blue = "\x1b[34m";
$red = "\x1b[31m";
$green = "\x1b[32m";
$white = "\x1b[37m";
$yellow = "\x1b[33m";
$cyan = "\x1b[36m";
$reset = "\x1b[0m";
$times = [];


function timeStart($key) {
    global $times;
    $times[$key] = microtime(true);
}

function timeEnd($key) {
    global $blue, $times, $reset;
    $end = microtime(true);

    echo $key." took: ".$blue.(($end - $times[$key]) * 1000).$reset."\n";
}

timeStart("load words");
$string = file_get_contents("words_dictionary.json");
$words = array_keys(json_decode($string, true));
timeEnd("load words");

function hashWord($word)
{
    return strlen($word);
}

$result = [];
timeStart("iterate over words");

foreach ($words as $word) {
    $hash = hashWord($word);
    if ($result[$hash]) {
        array_push($result[$hash], $word);
    } else {
        $innerWords = [$word];
        $result[$hash] = $innerWords;
    }
}
timeEnd("iterate over words");


if (count($result) < count($words)) {
    foreach ($result as $hash => $innerWords) {
        $collisions = count($innerWords);
        if ($collisions > 1) {
            echo "Found: " . $white .
                join(', ', array_slice($innerWords, 0, 10)) . ($collisions > 10 ? ", ..." : "") .
                $reset .
                " for " . $cyan . $hash . $reset .
                " so " . $red . $collisions . $reset . " collisions\n";
        }
    }
    echo "There was a total of " . $red . (count($words) - count($result)) . $reset . " collisions";
} else {
    echo $green . "WOW, no collision" . $reset . "\n";
}
