<?php session_start();
$language = (!empty($_COOKIE['language'])) ? $_COOKIE['language'] : 'fr';
$language = (isset($_POST['l'])) ? htmlspecialchars($_POST['l']) : $language;
if (isset($_POST['l'])) setcookie('language', $language, [ 'expires' => time() + 365*24*3600, 'secure' => true, 'httponly' => true,]);
if ($language == 'en')
    $lng = 1;
else
    $lng = 0;
$updtLBtexts = [
    [ "Carte du monde", "World map" ],
    [ "Liste des joueurs", "Player list"],
    [ "Jouer !", "Play!" ],
    [ "vous", "you" ],
    [ "Quelque chose de bizarre s'est passé...", "Something wrong has occured..."],
    [ "Partagez la partie", "Share the game" ],
    [ "Cliquez pour copier", "Click to copy" ],
    [ "Copié !", "Copied!" ],
    [ "Impossible de copier !", "Failed to copy!" ],
    [ "Expulser ", "Kick " ],
    [ "Souhaitez-vous vraiment expulser %s ?", "Do you really want to kick %s ?"],
    [ "Notifier ", "Ping " ],
    [ "Demander son pays", "Ask for his country" ],
    [ "<span class='bold'>%s</span><br>(Équipe %s)", "<span class='bold'>%s</span><br>(%s team)" ],
    [ "Tous les joueurs n'ont pas sélectionné de pays", "Some players have not selected a country" ],
];

include('../debug.php');

if ( !(isset($_SESSION['ID']) && isset($_SESSION['gameID']) && isset($_SESSION['game']) && isset($_SESSION['nickname'])) ) {
    include('includes/clear.php');
    header("Location: ../../");
    exit();
}

include_once('../fcts/db.php');
include_once('../fcts/lobby.php');

$gameData = gameStatus($_SESSION['ID'], $_SESSION['gameID']);
if (!$gameData['found'])
{
    $_SESSION['errorMsg'] = $updtLBtexts[4][$lng];
    header("Location: ../../");
    exit();
}

$_SESSION['game'] = $gameData['game'];
include_once('lobby/ready.php');

?>
<script>
function copy(text) {
    var copyText = document.createElement('input');
    copyText.setAttribute('value', text);
    document.body.appendChild(copyText);
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(copyText.value);
    document.body.removeChild(copyText);
}
</script>
<div id="lobbyDiv">
    <div id="lbMap">
        <h3 class="worldmaptitle"><?php echo $updtLBtexts[0][$lng] ?></h3>
        <img id="helpcat" src="images/cats/specialcat/helpcatcut.png">
        <?php include_once('lobby/map.php') ?>
    </div>
    <div id="lbSeparator"></div>
    <div id="lbLeftPanel">

        <?php include_once('lobby/playerList.php') ?>

        <?php if (isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID']) { ?>
        <hr id="lbhr">
        <?php if ($everyoneIsReady) echo "<form method='post'>
        <input type='hidden' name='starting' value='start'>" ?>
        <div id="lbCommand">
            <button id="btnstartgame" <?php if (!$everyoneIsReady) echo 'disabled onmouseover="disabledstart(true)" onmouseout="disabledstart(false)" ontouchstart="disabledstart(true)" ontouchend="disabledstart(false)"'; ?>><?php echo $updtLBtexts[2][$lng] ?></button>
            <?php if (!$everyoneIsReady) { ?>
                <br>
                <span class="infodisabled">
                    
                </span>
            <?php } ?>
        </div>
        <?php if ($everyoneIsReady) {
            echo "</form>";
        } else {?>
        <script>
            function disabledstart(over) {
                if (over)
                    document.getElementById("btnstartgame").innerHTML = "<?php echo $updtLBtexts[14][$lng]; ?>";
                else
                    document.getElementById("btnstartgame").innerHTML = "<?php echo $updtLBtexts[2][$lng]; ?>";
            }
        
        </script>
        <?php }
    } ?>

    </div>
</div>

<?php include_once('lobby/lobbyjs.php') ?>