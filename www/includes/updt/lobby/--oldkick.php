<?php session_start();
$language = (!empty($_COOKIE['language'])) ? $_COOKIE['language'] : 'fr';
$language = (isset($_POST['l'])) ? htmlspecialchars($_POST['l']) : $language;
if (isset($_POST['l'])) setcookie('language', $language, [ 'expires' => time() + 365*24*3600, 'secure' => true, 'httponly' => true,]);
if ($language == 'en')
    $lng = 1;
else
    $lng = 0;
$ttexts = [
    [ "fr", "en" ],
    [ "Vous ne pouvez pas vous éjecter !", "You can't kick yourself!"],
    [ "Le joueur %s n'est pas connecté !", "The player %s is not connected!"],
    [ "Vous n'avez pas", "The %s team doesn't exist..."],
    [ "La partie est terminée", "The game has ended"],
    [ "Une erreur est survenue", "An error has occured"],
    [ "L'équipe %s n'est plus disponible", "The %s team is no longer available"]
];

include_once('../../fcts/db.php');
include_once('../../fcts/lobby.php');


if ( !(isset($_SESSION['ID']) && isset($_SESSION['gameID']) && isset($_SESSION['game']) && isset($_SESSION['nickname'])) ) {
    include('../../clear.php');
    header("Location: ../../../");
    exit();
}
if (!isset($_POST['color']))
{
    echo '[0,"' . $ttexts[2][$lng] . '"]';
    exit();
}
elseif (isset($countrylistobj[htmlspecialchars($_POST['color'])])) {
    $colorchoice = htmlspecialchars($_POST['color']);
    $joinData = gameStatus($_SESSION['ID'], $_SESSION['gameID']);
    if ($joinData['found']) {
        
        $_SESSION['game'] = $joinData['game'];
        if (strlen($joinData['game']['playerList']) > 0) {
            $playersArray = explode('┇', $joinData['game']['playerList']);
            
            $playernb = 0;
            foreach ($playersArray as $playerobj) {
                if (strlen($playerobj) > 0) {
                    $playerData = explode('┊', $playerobj);

                    if ($colorchoice != 'none' && $playerData[1] == $countrylistobj[$colorchoice] && !str_starts_with($playerobj, $_SESSION['nickname'] . '┊')) {
                        echo '[0,"' .sprintf($ttexts[6][$lng], $colorchoice) . '"]';
                        exit();
                    }

                    if (str_starts_with($playerobj, $_SESSION['nickname'] . '┊')) {
                        $playerData[1] = $countrylistobj[$colorchoice];
                    }

                    $playersArray[$playernb] = implode('┊', $playerData);
                    $playernb++;
                }

            }
            $playerList = implode('┇', $playersArray);
            $_SESSION['game'] = updateTeamLobby($_SESSION['ID'], $_SESSION['gameID'], $playerList);
            $_SESSION['color'] = $colorchoice;
            echo '[1,"' .sprintf($ttexts[1][$lng], $_SESSION['nickname'], $colorchoice) . '"]';
        }
        else {
            echo '[0,"' .$ttexts[4][$lng] . '"]';
        }
    }
    else
        echo '[0,"' .$ttexts[4][$lng] . '"]';
}
else
    echo '[0,"' . sprintf($ttexts[3][$lng], htmlspecialchars($_POST['color'])) . '"]';

?>