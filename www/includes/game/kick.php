<?php session_start();
$language = (!empty($_COOKIE['language'])) ? $_COOKIE['language'] : 'fr';
$language = (isset($_POST['l'])) ? htmlspecialchars($_POST['l']) : $language;
if (isset($_POST['l'])) setcookie('language', $language, [ 'expires' => time() + 365*24*3600, 'secure' => true, 'httponly' => true,]);
if ($language == 'en')
    $lng = 1;
else
    $lng = 0;
$ktexts = [
    [ "fr", "en" ],
];

include_once('../fcts/db.php');
include_once('../fcts/lobby.php');

if (isset($_POST['kickplayer']) && isset($_POST['kickpseudo']) && isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID']) {
    $quitstatus = quitLobby( $lng, $_SESSION['ID'], $_SESSION['gameID'], htmlspecialchars($_POST['kickpseudo']), 1 );
    if ($quitstatus['found'])
        echo('[true, "kick", 0, "' . $quitstatus['infos'] . '", "' . $_SESSION['ID'] . '|'. $_SESSION['gameID'] . '", "' . htmlspecialchars($_POST['kickpseudo']) . '"]');
    else
        echo('[true, "kick", 1, "' . $quitstatus['infos'] . '", "' . $_SESSION['ID'] . '|'. $_SESSION['gameID'] . '", "' . htmlspecialchars($_POST['kickpseudo']) . '"]');
}
else 
    echo('[false,"kick", 0,""]')
?>