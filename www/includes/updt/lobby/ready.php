<?php

$countrylist = [
    '1' => 'black',
    '2' => 'gray',
    '3' => 'red',
    '4' => 'green',
    '5' => 'yellow',
    '6' => 'blue',
    '7' => 'white',
];

$maptxt = [
    [ " (Vous)", " (You)"]
];

$countryplayerlist = [ '1' => '', '2' => '', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '' ];
$everyoneIsReady = true;

if (strlen($_SESSION['game']['playerList']) > 0) {
    $playersArray = explode('┇', $_SESSION['game']['playerList']);
    
    foreach ($playersArray as $playerobj) {
        if (strlen($playerobj) > 0) {
            $playerData = explode('┊', $playerobj);
        
            if (isset($countrylist[$playerData[1]])) {
                if ($playerData[0] == $_SESSION['nickname'])
                    $countryplayerlist[$playerData[1]] = $playerData[0] . $maptxt[0][$lng];
                else
                    $countryplayerlist[$playerData[1]] = $playerData[0];
            }
            else {
                $everyoneIsReady = false;
            }
        }
    }
}

?>