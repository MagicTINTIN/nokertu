<?php session_start();
// echo "GAME>>> " . $_SERVER['QUERY_STRING'] . "<br>";
// if (isset($_REQUEST["gameid"])) {
//     echo htmlspecialchars($_REQUEST["gameid"]);
// }

$language = (!empty($_COOKIE['language'])) ? $_COOKIE['language'] : 'fr';
$language = (isset($_POST['l'])) ? htmlspecialchars($_POST['l']) : $language;
if (isset($_POST['l'])) setcookie('language', $language, [ 'expires' => time() + 365*24*3600, 'secure' => true, 'httponly' => true,]);
if ($language == 'en')
    $lng = 1;
else
    $lng = 0;
$texts = [
    [ "fr", "en" ],
    [ "Connexion", "Connection" ],
    [ "Entrez un pseudonyme", "Enter a nickname" ],
    [ "Pseudo", "Nickname" ],
    [ "Entrer", "Enter" ],
    [ "Aucune partie trouvée", "No game found"],
    [ "Choisissez un pseudo pour rejoindre la partie", "Choose a nickname to join the game"],
    [ "Une erreur est survenue...", "An error has occured..." ],
    [ "Bienvenue dans le lobby %s !", "Welcome to the lobby %s!" ],
    [ "Ce pseudo ne respecte pas les conditions demandées !", "This nickname doesn't match the requirements!" ],
];

include('includes/debug.php');

include_once('includes/fcts/tools.php');
include_once('includes/fcts/db.php');
include_once('includes/fcts/lobby.php');

$maxNickSize = 18;
$minNickSize = 3;

if (!(isset($_REQUEST['gameid']) || isset($_POST['create']) || (isset($_POST['gameid']) && isset($_POST['nickname'])))) {
    $_SESSION['errorMsg'] = $texts[5][$lng];
    header("Location: ./");
    exit();
}

if (isset($_POST['create'])) {
    $newGameDataRaw = createLobby($lng);
    if ($newGameDataRaw['success']) {
        $_SESSION['gameOwner'] = $newGameDataRaw['ID'];
        $_SESSION["infoMsg"] = $newGameDataRaw['reason'];
        $_SESSION["gameID"] = $newGameDataRaw['gameID'];
    }
    else {
        $errorMessageAG = $newGameDataRaw['reason'];
    }
}

elseif (isset($_POST['gameid']) && isset($_POST['nickname'])) {
    $nickname = preg_replace('/[^a-zA-Z0-9_-]/', "_", htmlspecialchars($_POST['nickname']));
    if (strlen($nickname) < $minNickSize) {
        $_SESSION['errorMsg'] = $texts[19][$lng];
    }
    else {
        $nickname = substr($nickname, 0, $maxNickSize);

        $joinData = joinLobby($lng, htmlspecialchars($_POST['gameid']), $nickname);
        if ($joinData['found']) {
            $_SESSION['infoMsg'] = sprintf($texts[8][$lng], $nickname);
            $_SESSION['game'] = $joinData['infos'];
            $_SESSION['gameID'] = $joinData['infos']['gameID'];
            $_SESSION['ID'] = $joinData['infos']['_uuid'];
            $_SESSION['nickname'] = $nickname;
            header("Location: ./lobby");
            exit();
        }
        else {
            $_SESSION['errorMsg'] = $joinData['reason'];
            if ($joinData['type'] != "pseudo") {
                header("Location: ./");
                exit();
            }
        }
    }
}

elseif (isset($_REQUEST['gameid'])) {
    unset($_SESSION['gameOwner']);
    $joinData = getJoinLobby($lng, htmlspecialchars($_REQUEST['gameid']));
    if ($joinData['found'])
        $_SESSION['infoMsg'] = $texts[6][$lng];
    else {
        $_SESSION['errorMsg'] = $joinData['reason'];
        if ($joinData['type'] != "pseudo") {
            header("Location: ./");
            exit();
        }
    }
}

if (isset($_SESSION['errorMsg'])) {
    $errorMessage = $_SESSION['errorMsg'];
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['infoMsg'])) {
    $infoMessage = $_SESSION['infoMsg'];
    unset($_SESSION['infoMsg']);
}
if (!( (isset($_POST['create']) && isset($_SESSION['gameID']))
    || isset($_REQUEST['gameid'])
    // || (isset($_SESSION['ID']) && isset($_SESSION['gameID']) && isset($_SESSION['game']) && isset($_SESSION['nickname'])) 
    )) {
    $_SESSION['errorMsg'] = $joinData['reason'];
    include('includes/clear.php');
    header("Location: ./");
    exit();
}
?>
<!DOCTYPE html>
<?php if (debug_mode(DEBUG_1)) echo "<!-- DEBUG MODE $DEBUGMODE -->\n" ?>
<html lang="<?php echo $texts[0][$lng] ?>" id="background" class="worldmap">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport"
        content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title><?php echo $texts[1][$lng] ?> | Nokertu</title>

    <link href="styles/vars.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/connection.css" rel="stylesheet">
    <link href="styles/nonGame.css" rel="stylesheet">
    <meta name="author" content="ALC ProduXion/Softplus">
    <meta name="description" content="Connection page to a game with cats which want to go to space">

    <?php include_once("./scripts/variablefcts.php"); ?>

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
</head>

<body>
    <div id="centeringbg">
        <section id="connecttogame" class="centeringnav">

                <?php include_once("includes/messages.php") ?>

                <h1><?php echo $texts[2][$lng] ?></h1>
                <form method="post">
                    <input type="text" id="pseudoinput" name="nickname" required
                        placeholder="<?php echo $texts[4][$lng] ?>"
                        pattern="[a-zA-Z0-9_-]+"
                        minlength="<?php echo $minNickSize ?>" maxlength="<?php echo $maxNickSize ?>" size="<?php echo $maxNickSize ?>" title="<?php echo $texts[2][$lng] ?>">
                        <br>
                    <input type="hidden" name="gameid" value="<?php echo (isset($_POST['create']) && isset($_SESSION['gameID'])) ? $_SESSION['gameID'] : htmlspecialchars($_REQUEST['gameid']) ?>" />
                    <input type="submit" id="enterpseudo" name="language" value="<?php echo $texts[4][$lng] ?>" />
                </form>
        </section>
        <?php include_once("includes/commonjslng.php") ?>
    </div>

    <script src="scripts/cookies.js"></script>
    <script src="scripts/mainfunctions.js"></script>

    <?php
    if (isset($_SESSION['lstWSmsg']))
        include_once("scripts/wsDisconnect.php");
    ?>
    
</body>

</html>