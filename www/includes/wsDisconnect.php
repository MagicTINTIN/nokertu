<script>

// Connect to the websocket
inGame = 0;
let socket;

const connect = function () {
    // Return a promise, which will wait for the socket to open
    return new Promise((resolve, reject) => {

        const socketProtocol = (window.location.protocol === 'https:' ? 'wss:' : 'ws:')
        const port = 3000;
        const socketUrl = `${socketProtocol}//${window.location.hostname}:${port}/ws/`

        socket = new WebSocket(socketUrl);

        socket.onopen = (e) => {
            // Connection message
            socket.send(JSON.stringify({
                "from": "Nokertu",
                "type": "load",
                "loaded": true
            }));
            // connection established
            resolve();
        }

        socket.onmessage = (data) => {
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('websocket sent', data);" ?>
            
            sendGame("<?php echo $_SESSION['lstWSmsg'] ?>",'playerQuit');
            socket.close();
        }

        socket.onclose = (e) => {
            // Return an error if any occurs
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('Disconnected from websocket');" ?>
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
    if (!gameid || gameid == 0) return <?php if (debug_mode(DEBUG_WEBSOCKET)) echo 'console.log("No gameid !");'; else echo "0;"; ?>
    if (isOpen(socket)) {
        socket.send(JSON.stringify({
            "from": "Nokertu",
            "type": type,
            "senttime": Date.now(),
            "gameid": gameid
        }));
        <?php if (debug_mode(DEBUG_WEBSOCKET)) echo 'console.log(`${type} sent to `, gameid);' ?>
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Connect to the websocket
    connect();
});

<?php unset($_SESSION['lstWSmsg']); ?>
</script>