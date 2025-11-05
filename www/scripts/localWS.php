<?php
$lclwsjstexts = [
    // ["Merci de choisir un pays !", "Please choose a country!"],
    // [" souhaiterait sélectionner votre pays", " would like to select your country"],
];

include_once(__DIR__ . "/../includes/constants.php");
?>

<script>
    // Connect to the websocket
    inGame = 0;
    let socket;
    $('#lobby').load('includes/updt/lobby.php');

    const connect = function() {
        // Return a promise, which will wait for the socket to open
        return new Promise((resolve, reject) => {

            const socketProtocol = 'wss:'; //(window.location.protocol === 'https:' ? 'wss:' : 'ws:')
            const socketAddress = "magictintin.fr"; //`${window.location.hostname}`;
            const port = 8443;
            const socketUrl = `${socketProtocol}//${socketAddress}:${port}`

            socket = new WebSocket(socketUrl); //, 'echo-protocol');

            socket.onopen = (e) => {
                <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('socket opened');" ?>
                // Connection message
                // socket.send("");
                // connection established
                resolve();
                sendGame("join");
            }

            socket.onmessage = (data) => {
                <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('websocket sent', data);" ?>

                <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log(`received \${data.data}`);" ?>

                // if (isInGame && data.data.startsWith('ping'))
                //         $('#gameDiv').load('includes/updateParts/ping.php');

                <?php
                // LOBBY WS UPDATE MESSAGES
                $state = gameStatus($_SESSION['gameID']);
                if ($state == 0) {
                ?>
                    const lobbyUpdateKeywords = ["join", "quit", "lobby"];
                    for (const keyword of lobbyUpdateKeywords) {
                        if (inGame == 1 && data.data.startsWith(keyword))
                            $('#lobby').load('includes/updt/lobby.php');
                    }
                <?php } ?>


                // let parsedData = JSON.parse(data.data);
                // if (parsedData.append === true) {
                //     if (parsedData.dataText.startsWith('WSConnectionOK'))
                //         ctgExec();
                //     else if (inGame == 1 && parsedData.dataText.startsWith('refreshLobby'))
                //         $('#lobby').load('includes/updt/lobby.php');
                //     else if (inGame == 1 && parsedData.dataText.startsWith('askch')) {
                //         let received = parsedData.dataText.split('|');
                //         if (received[received.length - 1] == "<?php echo $_SESSION['nickname'] ?>")
                //             showWarning(received[Math.max(0,received.length - 2)] + "<?php //echo $lclwsjstexts[1][$lng] ?>");
                //     }
                //     else if (inGame == 1 && parsedData.dataText.startsWith('pingch')) {
                //         let received = parsedData.dataText.split('|');
                //         if (received[received.length - 1] == "<?php echo $_SESSION['nickname'] ?>")
                //             showWarning("<?php //echo $lclwsjstexts[0][$lng] ?>");
                //     }
                //     // if (isInGame && parsedData.dataText.startsWith('Game ping'))
                //     //     $('#gameDiv').load('includes/updateParts/ping.php');
                // }
            }

            socket.onerror = (e) => {
                // Return an error if any occurs
                <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log(e);" ?>
                resolve();
                // Try to connect again
                connect();
            }

            socket.onclose = (e) => {
                // Return an error if any occurs
                // console.log('Disconnected from websocket', e);
                console.log("Reconnecting to websocket...");
                // $('#messages').load('printMessagesPart.php');
                setTimeout(() => {
                    connect();
                }, 500);
            }
        });
    }

    // check if a websocket is open
    const isOpen = function(ws) {
        return ws.readyState === ws.OPEN
    }

    function sendGame(type = 'ping') {
        gameid = "<?php echo $_SESSION['gameID'] ?>";
        // console.log('sending val ping', gameid);
        if (!gameid || gameid == 0 || gameid == "") return <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('no gameid !')";
                                                            else echo "0"; ?>;
        if (isOpen(socket)) {
            socket.send(`<?php echo NOKERTU_WS_ROOM ?>/${gameid}:${type}`);
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo 'console.log(`${type} sent to ${gameid}`);' ?>
        }
    }
</script>