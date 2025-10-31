<?php
include_once('includes/fcts/db.php');
include_once('includes/fcts/lobby.php');

if (isset($_SESSION['nickname']) && isset($_SESSION['gameID']) && isset($_SESSION['ID'])) {
    $quitstatus = quitLobby( $lng, $_SESSION['ID'], $_SESSION['gameID'], $_SESSION['nickname'] );
    if ($quitstatus['found'])
        $_SESSION['infoMsg'] = $quitstatus['infos'];
        $_SESSION['lstWSmsg'] = $_SESSION['ID'] . '|'. $_SESSION['gameID'];
}

unset($_SESSION['gameOwner']);
unset($_SESSION['gameID']);
unset($_SESSION['game']);
unset($_SESSION['ID']);
unset($_SESSION['nickname']);
unset($_SESSION['color']);
?>