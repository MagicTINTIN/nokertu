<?php
include_once('includes/fcts/db.php');
include_once('includes/fcts/lobby.php');

$kicktxt = [ "Vous avez été expulsé(e) de la partie", "You have been kicked from the game"];

if (isset($_SESSION['nickname']) && isset($_SESSION['gameID']) && isset($_SESSION['ID'])) {
    $_SESSION['errorMsg'] = $kicktxt[$lng];
}

unset($_SESSION['gameOwner']);
unset($_SESSION['gameID']);
unset($_SESSION['game']);
unset($_SESSION['ID']);
unset($_SESSION['nickname']);
unset($_SESSION['color']);
?>