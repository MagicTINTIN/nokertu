<?php

include_once(__DIR__ . "/party.php");

function getLobby(string $gameID): array
{
    global $db;
    $gamesStatement = $db->prepare('SELECT _uuid, gameID, timestamp, gameState, maxPlayers FROM games WHERE gameID = :gameID AND gamestate > -1');
    $gamesStatement->execute(['gameID' => $gameID]);
    $games = $gamesStatement->fetchAll();

    $runninggame = stopRunningAll($games);
    if (!$runninggame['val']) {

        return [
            'found' => true,
            'game' => $games[$runninggame['active']],
            'nbConnected' => get_connected_players_count($gameID)
        ];
    } else
        return [
            'found' => false,
            'reason' => 'No game found with this ID'
        ];
}

function getJoinLobby(int $lng,  string $gameID): array
{
    $jlbtxt = [
        ["Aucune partie avec le code %s trouvée", "No game found with the code %s"],
        ["La partie %s a déjà commencé ! Vous ne pouvez plus la rejoindre...", "The game %s has already started ! You can no longer join it..."],
        ["La partie %s est déjà pleine. (%d/%d)", "The game %s is already full. (%d/%d)"],
    ];

    $lobbydata = getLobby($gameID);
    if ($lobbydata['found']) {
        $actgame = $lobbydata['game'];

        if ($lobbydata['nbConnected'] >= $actgame['maxPlayers'])
            return [
                'found' => false,
                'type' => 'full',
                'reason' => sprintf($jlbtxt[2][$lng], $gameID, $lobbydata['nbConnected'], $actgame['maxPlayers'])
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
    } else
        return [
            'found' => false,
            'type' => 'exist',
            'reason' => sprintf($jlbtxt[0][$lng], $gameID)
        ];
}

function joinLobby(int $lng, string $gameID, string $nickname): array
{
    $jlntxt = [
        ["Ce pseudo est déjà pris !", "This nickname is already taken!"],
    ];

    global $db;
    $lobby = getJoinLobby($lng, $gameID);

    if (!$lobby['found'])
        return $lobby;

    if (!is_username_available($gameID, $nickname))
        return [
            'found' => false,
            'type' => 'pseudo',
            'reason' => $jlntxt[0][$lng],
        ];
    # nickname | team selection | ready

    add_player_to_lobby($gameID, $nickname);
    return $lobby;
}

function quitLobby(int $lng, int $_UUID, string $gameID, string $nickname, int $reason = 0): array
{
    $qlbtxt = [
        ["La partie dans laquelle se trouve %s n'a pas été trouvée", "The game %s is in was not found"],
        ["%s n'était déjà plus dans la partie", "%s was already out of the game"],
        [["Vous (%s) avez correctement été déconnecté de la partie %s", "%s a bien été expulsé de la partie %s"], ["You (%s) have been successfully disconnected from the game %s", "%s has been successfully kicked from the game %s"]],
        ["La partie %s a bien été supprimée en vous déconnectant", "The game %s have successfully been deleted when you disconnected yourself"],
    ];

    global $db;

    $lobby = getLobby($gameID);

    if (!$lobby['found'])
        return [
            'found' => false,
            'infos' => sprintf($qlbtxt[0][$lng], $nickname)
        ];

    remove_player_from_lobby($gameID, $nickname);

    if (get_connected_players_count($gameID) == 0) {
        $sqlQuery = 'UPDATE games SET gameState = -1 WHERE gameID = :gameID AND _uuid = :_uuid';

        $updateGame = $db->prepare($sqlQuery);
        $updateGame->execute([
            '_uuid' => $_UUID,
            'gameID' => $gameID,
        ]);

        return [
            'found' => true,
            'infos' => sprintf($qlbtxt[3][$lng], $gameID)
        ];
    }

    return [
        'found' => true,
        'infos' => sprintf($qlbtxt[2][$lng][$reason], $nickname, $gameID)
    ];
}

function createLobby(int $lng): array
{
    global $db;

    $lbtxt = [
        ["Partie créée avec succès, code de la partie : ", "Game successfully created, game code : "],
        ["Impossible de générer une nouvelle partie pour le moment", "Impossible to generate a new game for the moment"],
        ["Une erreur est survenue lors de la création de la partie", "An error has occured while creating the new game"],
    ];


    $try = 0;
    $maxTry = 10000;
    $gameID = generateRandomGameID();
    $gameIDfound = false;

    while (!$gameIDfound && $try < $maxTry) {
        $try++;
        $gamesStatement = $db->prepare('SELECT _uuid, timestamp FROM games WHERE gameID = :gameID AND gameState > -1');
        $gamesStatement->execute(['gameID' => $gameID]);
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
        $gamesStatement->execute(['gameID' => $gameID]);
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
    } else
        return [
            'success' => false,
            'reason' => $lbtxt[1][$lng]
        ];
}

function setupGame(string $gameid): bool
{
    error_log("Setting game : " . $gameid);
    global $db;

    if (get_connected_players_count($gameid) < 1) return false;

    // TODO: 
    // add cards to all players

    return true;
}
