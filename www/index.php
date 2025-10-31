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
    [ "Comment jouer", "How to play"],
    [ "Créer une partie", "Create a game" ],
    [ "À propos", "About" ],
    [ "Jouez à Nokertu", "Play Nokertu" ],
    [ "Entrez le code que l'on vous a partagé","Enter the code shared with you" ],
    [ "Jouer", "Play" ],
];

include('includes/debug.php');

include('includes/clear.php');

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
<?php if ($DEBUGMODE % 3 == 0) echo "<!-- DEBUG MODE $DEBUGMODE -->\n" ?>
<html lang="<?php echo $texts[0][$lng] ?>" id="background" class="worldmap">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport"
        content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title>Nokertu</title>

    <link href="styles/vars.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/welcome.css" rel="stylesheet">
    <link href="styles/nonGame.css" rel="stylesheet">
    <meta name="author" content="ALC ProduXion/Softplus">
    <meta name="description" content="Game with cats which want to go to space">
    
    <?php include_once("./scripts/variablefcts.php"); ?>

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
</head>

<body>
    <div id="centeringbg">
        <section id="connecttogame" class="centeringnav">

            <?php include_once("includes/messages.php") ?>

            <img id="welcomelogo" src="images/logo.png">
            <h1><?php echo $texts[4][$lng] ?></h1>
            <form action="connection" method="post">
                <input type="text" id="gameidinput" name="gameid" required
                    placeholder="000000"
                    pattern="[0-9]{6}"
                    minlength="6" maxlength="6" size="6" title="<?php echo $texts[5][$lng] ?>">
                    <br>
                <input type="submit" id="entergameid" name="entergamesub" value="<?php echo $texts[6][$lng] ?>" />
            </form>
        </section>

        <footer class="centeringfoot">
            <form method="post">
                <input type="submit" name="rules" value="<?php echo $texts[1][$lng] ?>" />
            </form>

            <form action="connection" method="post">
                <input type="hidden" name="creation" value="true" />
                <input type="submit" name="create" value="<?php echo $texts[2][$lng] ?>" />
            </form>

            <form method="post">
                <input type="submit" name="about" value="<?php echo $texts[3][$lng] ?>" />
            </form> 
        </footer>
        <?php include_once("includes/commonjslng.php") ?>
    </div>

    <script src="scripts/cookies.js"></script>
    <script src="scripts/mainfunctions.js"></script>

    <?php
    if (isset($_SESSION['lstWSmsg']))
        include_once("includes/wsDisconnect.php");
    ?>
</body>

</html>