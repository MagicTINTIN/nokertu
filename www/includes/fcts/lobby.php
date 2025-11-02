<?php 

function stopRunning ( array $gameData) : bool
{
    $db = dbConnect();

    # Games started 48h ago are deleted
    if (time() - $gameData['timestamp'] > 3600 * 24 * 2) {
            $sqlQuery = 'UPDATE games SET gameState = -1 WHERE _uuid = :_uuid';

            $updateGame = $db->prepare($sqlQuery);
            $updateGame->execute([
                '_uuid' => $gameData['_uuid'],
            ]);
            return true;
    }
    else
        return false;
}

function stopRunningAll( array $gamesData ) : array
{
    $rtnval = ['val' => true, 'active' => -1];
    $gamenb = -1;
    foreach ($gamesData as $gameData) {
        $gamenb++;
        if (!stopRunning($gameData))
        {
            $rtnval = ['val' => false, 'active' => $gamenb];
        }
    }
    return $rtnval;
    
}

function getLobby( string $gameID ) : array
{
    $db = dbConnect();
    $gamesStatement = $db->prepare('SELECT _uuid, gameID, timestamp, gameState FROM games WHERE gameID = :gameID AND gamestate > -1');
    $gamesStatement->execute([ 'gameID' => $gameID ]);
    $games = $gamesStatement->fetchAll();

    $runninggame = stopRunningAll($games);
    if (!$runninggame['val'])
        return [
            'found' => true,
            'game' => $games[$runninggame['active']]
        ];
    else 
        return [
            'found' => false,
            'reason' => 'No game found with this ID'
        ];
}

function getJoinLobby(int $lng,  string $gameID) : array
{
    $jlbtxt = [
        [ "Aucune partie avec le code %s trouvée","No game found with the code %s" ],
        [ "La partie %s a déjà commencé ! Vous ne pouvez plus la rejoindre...", "The game %s has already started ! You can no longer join it..." ],
        [ "La partie %s est déjà pleine.", "The game %s is already full." ],
    ];

    $lobbydata = getLobby($gameID);
    if ($lobbydata['found']) {
        $actgame = $lobbydata['game'];
        if ($actgame['nbConnected'] >= 7)
            return [
                'found' => false,
                'type' => 'full',
                'reason' => sprintf($jlbtxt[2][$lng], $gameID)
            ];
        elseif ($actgame['gameState'] > 0)
            return [
                'found' => false,
                'type' => 'started',
                'reason' => sprintf($jlbtxt[1][$lng], $gameID)
            ];
        else
            return [
                'found' => true,
                'infos' => $actgame
            ];
    }
    else
        return [
            'found' => false,
            'type' => 'exist',
            'reason' => sprintf($jlbtxt[0][$lng], $gameID)
        ];
}

function joinLobby( int $lng, string $gameID, string $nickname ) : array
{   
    $jlntxt = [
        [ "Ce pseudo est déjà pris !","This nickname is already taken!" ],
    ];
    $db = dbConnect();
    $lobby = getJoinLobby( $lng, $gameID );

    if (!$lobby['found'])
        return $lobby;
    
    if (strlen($lobby['infos']['playerList']) > 0) {
        $playersArray = explode('┇', $lobby['infos']['playerList']);

        $nbfound = 0;
        foreach ($playersArray as $playerobj) {
            if (str_starts_with($playerobj, $nickname . '┊')) {
                $nbfound++;
            }
        }
        if ($nbfound>0)
            return [
                'found' => false,
                'type' => 'pseudo',
                'reason' => $jlntxt[0][$lng],
            ];
    }
    else
        $playersArray = [];
    # nickname | team selection | ready
    
    $playersArray[] = sprintf('%s┊0┊0', $nickname);
    $playersUpdated = implode('┇', $playersArray);

    $sqlQuery = 'UPDATE games SET playerList = :players, nbConnected = :nbco  WHERE gameID = :gameID';

    $updateGame = $db->prepare($sqlQuery);
    $updateGame->execute([
        'gameID' => $gameID,
        'players' => $playersUpdated,
        'nbco' => count($playersArray),
    ]);
    
    return $lobby;
}

function quitLobby( int $lng, int $_UUID, string $gameID, string $nickname, int $reason = 0 ) : array
{
    $qlbtxt = [
        [ "La partie dans laquelle se trouve %s n'a pas été trouvée","The game %s is in was not found" ],
        [ "%s n'était déjà plus dans la partie", "%s was already out of the game" ],
        [ [ "Vous (%s) avez correctement été déconnecté de la partie %s", "%s a bien été expulsé de la partie %s"], ["You (%s) have been successfully disconnected from the game %s", "%s has been successfully kicked from the game %s"] ],
        [ "La partie %s a bien été supprimée en vous déconnectant", "The game %s have successfully been deleted when you disconnected yourself" ],
    ];

    $db = dbConnect();
    $lobby = getLobby( $gameID );

    if (!$lobby['found'])
        return [
            'found' => false,
            'infos' => sprintf($qlbtxt[0][$lng], $nickname)
        ];
    
    $nbejected = 0;

    if (strlen($lobby['game']['playerList']) > 0) {
        $playersArray = explode('┇', $lobby['game']['playerList']);
        
        foreach ($playersArray as $playerobj) {
            if (str_starts_with($playerobj, $nickname . '┊')) {
                $playersArray = array_diff($playersArray, array("$playerobj"));
                $nbejected++;
            }
        }
    }
    else {
        $sqlQuery = 'UPDATE games SET gameState = -1 WHERE gameID = :gameID AND _uuid = :_uuid';

        $updateGame = $db->prepare($sqlQuery);
        $updateGame->execute([
            '_uuid' => $_UUID,
            'gameID' => $gameID,
        ]);

        return [
            'found' => false,
            'infos' => sprintf($qlbtxt[3][$lng], $gameID)
        ];
    }
    
    $playersUpdated = implode('┇', $playersArray);

    if ($nbejected > 0) {

        $newcount = count($playersArray);
        $newstate = ($newcount == 0) ? 3 : $lobby['game']['gameState'];

        $sqlQuery = 'UPDATE games SET playerList = :players, nbConnected = :nbco, gameState = :gameState WHERE gameID = :gameID AND _uuid = :_uuid';

        $updateGame = $db->prepare($sqlQuery);
        $updateGame->execute([
            '_uuid' => $_UUID,
            'gameID' => $gameID,
            'players' => $playersUpdated,
            'nbco' => $newcount,
            'gameState' => $newstate,
        ]);

        if ($newstate == 3)
            return [
                'found' => true,
                'infos' => sprintf($qlbtxt[3][$lng], $gameID)
            ];
        else
            return [
                'found' => true,
                'infos' => sprintf($qlbtxt[2][$lng][$reason], $nickname, $gameID)
            ];
    }
    else
        return [
            'found' => false,
            'infos' => sprintf($qlbtxt[1][$lng], $nickname)
        ];
}

function createLobby(int $lng) : array
{
    $db = dbConnect();

    $lbtxt = [
        [ "Partie créée avec succès, code de la partie : ","Game successfully created, game code : " ],
        [ "Impossible de générer une nouvelle partie pour le moment", "Impossible to generate a new game for the moment" ],
        [ "Une erreur est survenue lors de la création de la partie", "An error has occured while creating the new game" ],
    ];


    $try = 0;
    $maxTry = 10000;
    $gameID = generateRandomGameID();
    $gameIDfound = false;
    
    while (!$gameIDfound && $try < $maxTry) {
        $try++;
        $gamesStatement = $db->prepare('SELECT _uuid, timestamp FROM games WHERE gameID = :gameID AND gameState > -1');
        $gamesStatement->execute([ 'gameID' => $gameID ]);
        $games = $gamesStatement->fetchAll();

        $nbgames = count($games);
        if ($nbgames == 0)
            $gameIDfound = true;
        elseif (stopRunningAll($games)['val'])
            $gameIDfound = true;
        else
            $gameID = generateRandomGameID();
    }
    if ($gameIDfound) {
        
        $sqlQuery = 'INSERT INTO games(gameID, name, timestamp) VALUES (:gameID, :name, :timestamp)';

        $insertGame = $db->prepare($sqlQuery);
        $insertGame->execute([
            'gameID' => $gameID,
            'name' => $gameID . "'s game",
            'timestamp' => time()
        ]);

        $gamesStatement = $db->prepare('SELECT _uuid FROM games WHERE gameID = :gameID AND gameState > -1');
        $gamesStatement->execute([ 'gameID' => $gameID ]);
        $games = $gamesStatement->fetchAll();

        $nbgames = count($games);

        if ($nbgames == 1)
            return [
                    'success' => true,
                    'reason' => $lbtxt[0][$lng] . $gameID,
                    'ID' => $games[0]['_uuid'],
                    'gameID' => $gameID
            ];
        else
            return [
                    'success' => false,
                    'reason' => $lbtxt[2][$lng],
            ];
    }
    else
        return [
                'success' => false,
                'reason' => $lbtxt[1][$lng]
        ];
}

function gameStatus(int $id, string $gameid) : array
{
    $db = dbConnect();

    $gameStatement = $db->prepare('SELECT gameState FROM games WHERE _uuid = :_uuid AND gameID = :gameid');
    $gameStatement->execute([ '_uuid' => $id, 'gameid' => $gameid ]);
    $games = $gameStatement->fetchAll();

    $nbgame = count($games);
    if ($nbgame != 1) return [ 'found' => false ];
    
    return [ 
        'found' => true,
        'game' => $games[0]
    ];
}

function updateTeamLobby(int $_UUID, string $gameID, string $playerList) : array
{
    $db = dbConnect();

    $sqlQuery = 'UPDATE games SET playerList = :playerList WHERE gameID = :gameID AND _uuid = :_uuid';

    $updateGame = $db->prepare($sqlQuery);
    $updateGame->execute([
        '_uuid' => $_UUID,
        'gameID' => $gameID,
        'playerList' => $playerList
    ]);

    return gameStatus($_UUID, $gameID);
}


function isEveryOneReady(int $id, string $gameid, int $lng) : array
{
    $ieortxt = [
        [ "Partie lancée avec %d joueur", "Started a game with %d player" ],
        [ "Tous les joueurs n'ont pas choisi leur pays", "All players didn't choose their country"],
        [ "Aucun joueur trouvé", "No player found"],
        [ "La partie n'a pas été trouvée", "Game not found"],
    ];
    $fctcountrylist = [
        '1' => 'black',
        '2' => 'gray',
        '3' => 'red',
        '4' => 'green',
        '5' => 'yellow',
        '6' => 'blue',
        '7' => 'white',
    ];
    
    $everyoneIsReady = true;
    $info = "";
    $searchgame = gameStatus($id, $gameid);

    if (!$searchgame['found'])
        return [ 'started' => false, 'info' => $ieortxt[3][$lng], 'playernb' => 0, 'players' => [] ];
    
    $game = $searchgame['game'];
    $playernb = 0;
    $playerList = [];

    if (strlen($game['playerList']) > 0) {
        $playersArray = explode('┇', $game['playerList']);

        foreach ($playersArray as $playerobj) {
            
            if (strlen($playerobj) > 0) {
                $playernb++;
                $playerData = explode('┊', $playerobj);
                
                if (isset($fctcountrylist[$playerData[1]])) {
                    $playerList['Player' . $playernb] = $playerData[1];
                }
                else {
                    $everyoneIsReady = false;
                    $info = $ieortxt[1][$lng];
                    //  . " ERR: " . $playerData[1] . " | " . $fctcountrylist['1']
                }
            }
        }
        if ($everyoneIsReady) $info = sprintf($ieortxt[0][$lng], $playernb) . (($playernb > 1) ? 's' : '');
    } else {
        $everyoneIsReady = false;
        $info = $ieortxt[2][$lng];
    }
    return [ 'started' => $everyoneIsReady, 'info' => $info, 'playernb' => $playernb, 'players' => $playerList ];
}

function setupGame(int $id, string $gameid, int $playercount, array $players) : bool
{
    error_log("Setting game : " . $id . " | " . $gameid . " : " . $playercount . '/' . count($players));
    if ($playercount < 1 || count($players) != $playercount) return false;
    $db = dbConnect();

    $listExec = [
        'ID' => $id,
        'gameID' => $gameid,
        'connected' => $playercount
    ];

    $sqlQuery = "UPDATE games SET ";

    for ($i=1; $i <= $playercount; $i++) { 
        $listExec['Player' . $i] = $players['Player' . $i];
        $sqlQuery .= "Player$i = :Player$i, ";
    }
    // beurk : Player1 = :Player1, Player2 = :Player2, Player3 = :Player3, Player4 = :Player4, Player5 = :Player5, Player6 = :Player6, Player7 = :Player7
    $sqlQuery .= 'nbConnected = :connected, gameState = 1 WHERE gameID = :gameID AND ID = :ID';
    // error_log($sqlQuery);
    // foreach ($listExec as $key => $value) {
    //     error_log("listExec['". $key . "']='" . $value . "'");
    // }

    $updateGame = $db->prepare($sqlQuery);
    $updateGame->execute($listExec);

    $games = $updateGame->fetchAll();

    $nbgame = count($games);
    error_log($nbgame . " game(s) updated\n");
    // if ($nbgame != 1) return false;
    return true;
}

?>