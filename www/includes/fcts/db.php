<?php
include_once(__DIR__ . "/../../../../nokertu_db.php");

$db = dbConnect();

// // nokertu_db.php contains:

// function dbConnect() : PDO
// {
//     try
//     {
//         $dbtoconnect = new PDO("mysql:host=localhost;dbname=nokertu;charset=utf8mb4", 'usr', 'passwordtochangewhichisnot1234');
//     }
//     catch (Exception $e)
//     {
//             die('Erreur : ' . $e->getMessage());
//     }
//     return $dbtoconnect;
// }
?>