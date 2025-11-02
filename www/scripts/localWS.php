<?php
$lclwsjstexts = [
    [ "Merci de choisir un pays !", "Please choose a country!" ],
    [ " souhaiterait sélectionner votre pays", " would like to select your country" ],
]; ?>

<script>

// Connect to the websocket
inGame = 0;
let socket;

const connect = function () {
    // Return a promise, which will wait for the socket to open
    return new Promise((resolve, reject) => {

        const socketProtocol = 'wss:'; //(window.location.protocol === 'https:' ? 'wss:' : 'ws:')
        const socketAddress = "magictintin.fr";//`${window.location.hostname}`;
        const port = 8443;
        const socketUrl = `${socketProtocol}//${socketAddress}:${port}`

        socket = new WebSocket(socketUrl);//, 'echo-protocol');

        socket.onopen = (e) => {
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('socket opened');" ?>
            // Connection message
            // socket.send("");
            // sendGame()
            // connection established
            resolve();
        }

        socket.onmessage = (data) => {
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('websocket sent', data);" ?>

            let parsedData = JSON.parse(data.data);
            if (parsedData.append === true) {
                if (parsedData.dataText.startsWith('WSConnectionOK'))
                    ctgExec();
                else if (inGame == 1 && parsedData.dataText.startsWith('refreshLobby'))
                    $('#lobby').load('includes/updt/lobby.php');
                else if (inGame == 1 && parsedData.dataText.startsWith('askch')) {
                    let received = parsedData.dataText.split('|');
                    if (received[received.length - 1] == "<?php echo $_SESSION['nickname'] ?>")
                        showWarning(received[Math.max(0,received.length - 2)] + "<?php echo $lclwsjstexts[1][$lng] ?>");
                }
                else if (inGame == 1 && parsedData.dataText.startsWith('pingch')) {
                    let received = parsedData.dataText.split('|');
                    if (received[received.length - 1] == "<?php echo $_SESSION['nickname'] ?>")
                        showWarning("<?php echo $lclwsjstexts[0][$lng] ?>");
                }
                // if (isInGame && parsedData.dataText.startsWith('Game ping'))
                //     $('#gameDiv').load('includes/updateParts/ping.php');
            }
        }

        socket.onerror = (e) => {
            // Return an error if any occurs
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log(e);" ?>
            resolve();
            // Try to connect again
            connect();
        }
    });
}

// check if a websocket is open
const isOpen = function (ws) {
    return ws.readyState === ws.OPEN
}

function sendGame(gameid, type = 'ping') {
    // console.log('sending val ping', gameid);
    if (!gameid || gameid == 0) return <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('no gameid !')"; else echo "0"; ?>;
    if (isOpen(socket)) {
        socket.send(sprintf("nokertu/%s:%s", gameid, type));
        <?php if (debug_mode(DEBUG_WEBSOCKET)) echo 'console.log(`${type} sent to `, gameid);' ?>
    }
}

</script>