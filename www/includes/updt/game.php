<?php session_start();
$language = (!empty($_COOKIE['language'])) ? $_COOKIE['language'] : 'fr';
$language = (isset($_POST['l'])) ? htmlspecialchars($_POST['l']) : $language;
if (isset($_POST['l'])) setcookie('language', $language, [ 'expires' => time() + 365*24*3600, 'secure' => true, 'httponly' => true,]);
if ($language == 'en')
    $lng = 1;
else
    $lng = 0;
$updtGtexts = [
    ["",""],
];

include('../debug.php');

if (!isset($_SESSION['gameMenu'])) exit(1);

if ($_SESSION['gameMenu'] == "base") {
    include_once("game/base.php");
}
elseif ($_SESSION['gameMenu'] == "vab") {
    include_once("game/vab.php");
}