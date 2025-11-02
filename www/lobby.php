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
    [ "Choisissez votre pays", "Choose your country" ],
    [ "SORTIR", "EXIT" ],
    [ "Une erreur est survenue lors du lancement", "An error has occured when starting"]
];

include('includes/debug.php');

if ( !(isset($_SESSION['ID']) && isset($_SESSION['gameID']) && isset($_SESSION['game']) && isset($_SESSION['nickname'])) ) {
    include('includes/clear.php');
    header("Location: ./");
    exit();
}

include_once('includes/fcts/db.php');
include_once('includes/fcts/lobby.php');

if (isset($_POST['starting']) && htmlspecialchars($_POST['starting']) == 'start' 
    && isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID']) {
    $starting = isEveryOneReady($_SESSION['ID'], $_SESSION['gameID'], $lng);

    if ($starting['started']) {
        if (setupGame($_SESSION['ID'], $_SESSION['gameID'], $starting['playernb'], $starting['players'])) {
            $_SESSION['infoMsg'] = $starting['info'];
            header("Location: ./game");
            exit();
        }
        else $_SESSION['errorMsg'] = $texts[3][$lng];

    }
    else $_SESSION['errorMsg'] = $starting['info'];
} 

if (isset($_SESSION['errorMsg'])) {
    $errorMessage = $_SESSION['errorMsg'];
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['infoMsg'])) {
    $infoMessage = $_SESSION['infoMsg'];
    unset($_SESSION['infoMsg']);
}
?>
<!DOCTYPE html>
<?php if (debug_mode(DEBUG_1)) echo "<!-- DEBUG MODE $DEBUGMODE -->\n" ?>
<html lang="<?php echo $texts[0][$lng] ?>" id="background" class="worldmap">

<head>
    <meta charset="utf-8">
    <?php include_once("includes/scale.php") ?>
    
    <title>Lobby <?php echo $_SESSION['gameID'] ?> | Nokertu</title>

    <link href="styles/vars.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/lobby.css" rel="stylesheet">
    <link href="styles/nonGame.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
    <?php include_once("./scripts/localWS.php"); ?>
    <?php include_once("./scripts/variablefcts.php"); ?>

    <meta name="author" content="ALC ProduXion/Softplus">
    <meta name="description" content="Game with cats which want to go to space">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
</head>

<body>
    <div id="centeringbg">
        <section id="lobbycontainer" class="centeringnav">

            <?php include_once("includes/messages.php") ?>

                <h1 id="choosecountryTitle"><?php echo $texts[1][$lng] ?></h1>
                <div id="lobby"></div>
        </section>

        <form method="post" id="exitlobby" action="./">
            <input type="submit" name="exiting" value="<?php echo $texts[2][$lng] ?>" />
        </form>

        <?php include_once("includes/commonjslng.php") ?>
    </div>

    <script src="scripts/cookies.js"></script>
    <script src="scripts/mainfunctions.js"></script>

    <script type="text/javascript">
        // When the document has loaded
        function ctgExec() {
            sendGame('<?php echo $_SESSION['gameID'] ?>', 'lobby');
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Connect to the websocket
            connect();
            inGame = 1;
        });

        // function askchoose(dest) {
        //     sendGame('<?php echo $_SESSION['ID'] . '|'. $_SESSION['gameID'] ?>', 'askCountry|<?php echo $_SESSION['nickname'] ?>|' + dest);
        // }

        <?php if ((isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID'])) {?>
        function kick(dest) {
            sendGame('<?php echo $_SESSION['ID'] ?>', "lobby_kick");
        }
        <?php } ?>
        
    </script>
    
</body>

</html>