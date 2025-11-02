<?php
include_once("../../includes/fcts/lobby.php");
if (isset($_REQUEST["id"]))
{
    $gameID = htmlspecialchars($_REQUEST["id"]);
    $lobbydata = getLobby($gameID);
    if ($lobbydata['found'])
        echo "yes";
    else 
        echo "no";
}
else {
    echo "error";
}
?>