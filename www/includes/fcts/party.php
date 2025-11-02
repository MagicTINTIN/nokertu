<?php

include_once(__DIR__ . "/../constants.php");
include_once(__DIR__ . "/db.php");
include_once(__DIR__ . "/requests.php");

function is_game_owner() : bool {
    return isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID'];
}

?>