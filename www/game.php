<?php session_start();
$language = (!empty($_COOKIE['language'])) ? $_COOKIE['language'] : 'fr';
$language = (isset($_POST['l'])) ? htmlspecialchars($_POST['l']) : $language;
if (isset($_POST['l'])) setcookie('language', $language, [ 'expires' => time() + 365*24*3600, 'secure' => true, 'httponly' => true,]);
if ($language == 'en')
    $lng = 1;
else
    $lng = 0;
$texts = [
    [ "fr", "en" ],
    [ "En jeu !", "In game !" ],
];

include('includes/debug.php');

if ( !(isset($_SESSION['ID']) && isset($_SESSION['gameID']) && isset($_SESSION['game']) && isset($_SESSION['nickname'])) ) {
    include('includes/clear.php');
    header("Location: ./");
    exit();
}

include_once('includes/fcts/db.php');

if (isset($_SESSION['errorMsg'])) {
    $errorMessage = $_SESSION['errorMsg'];
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['infoMsg'])) {
    $infoMessage = $_SESSION['infoMsg'];
    unset($_SESSION['infoMsg']);
}

// TEMP ! NEED TO BE CHANGED
$_SESSION['gameMenu'] = 'base';

?>
<!DOCTYPE html>
<?php if (debug_mode(DEBUG_1)) echo "<!-- DEBUG MODE $DEBUGMODE -->\n" ?>
<html lang="<?php echo $texts[0][$lng] ?>" id="background" class="backgroundcontainer">

<head>
    <meta charset="utf-8">
    <?php include_once("includes/scale.php") ?>
    
    <title><?php echo $texts[1][$lng] ?> | Nokertu</title>

    <link href="styles/vars.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/game.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
    <?php include_once("./scripts/localWS.php"); ?>
    <?php include_once("./scripts/variablefcts.php"); ?>

    <meta name="author" content="ALC ProduXion/Softplus">
    <meta name="description" content="Game with cats which want to go to space">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
</head>

<body class="base">

    <section id="leftControlPanel">
        <div id="tlcontrolp" class="control"></div>
        <div id="blcontrolp" class="control"></div>
    </section>
    <section id="game"></section>

    <?php include_once("includes/commonjslng.php") ?>
    <?php include_once("includes/messages.php") ?>

    <script src="scripts/cookies.js"></script>
    <script src="scripts/mainfunctions.js"></script>

    <script type="text/javascript">
        // When the document has loaded
        function ctgExec() {
            sendGame('<?php echo $_SESSION['ID'] . '|'. $_SESSION['gameID'] ?>', 'connect');
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Connect to the websocket
            connect();
            inGame = 1;
        });

        $('#game').load('includes/updt/game.php');
    </script>
    
</body>

</html>