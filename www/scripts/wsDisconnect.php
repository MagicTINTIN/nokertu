<?php include_once(__DIR__ . "/../includes/constants.php"); ?>
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

        socket = new WebSocket(socketUrl);

        socket.onopen = (e) => {
            // Connection message
            sendGame('quit');
            // connection established
            resolve();
            socket.close();
        }

        socket.onmessage = (data) => {
            <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('websocket sent', data);" ?>
            
            sendGame('quit');
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

function sendGame(type = 'ping') {
    gameid = "<?php echo $_SESSION['lstWSmsg'] ?>";
    // console.log('sending val ping', gameid);
    if (!gameid || gameid == 0 || gameid == "") return <?php if (debug_mode(DEBUG_WEBSOCKET)) echo "console.log('no gameid !')"; else echo "0"; ?>;
    if (isOpen(socket)) {
        socket.send(`<?php echo NOKERTU_WS_ROOM ?>/${gameid}:${type}`);
        <?php if (debug_mode(DEBUG_WEBSOCKET)) echo 'console.log(`${type} sent to `, gameid);' ?>
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Connect to the websocket
    connect();
});

<?php unset($_SESSION['lstWSmsg']); ?>
</script>