<?php

function get_connected_players_count($gameID)
{
    global $db;

    $stmt = $db->prepare("SELECT COUNT(*) AS playerCount FROM players WHERE gameID = :gameID");
    $stmt->execute(['gameID' => $gameID]);

    $playerCount =  $stmt->fetchColumn();

    // $row = $result->fetch_assoc();
    // $playerCount = $row['playerCount'] ?? 0;
    return $playerCount;
}

function is_username_available(string $gameID, string $username) : bool
{
    if (strlen($username) > USERNAME_MAX_LENGTH) return false;
    global $db;

    $stmt = $db->prepare("SELECT COUNT(*) AS playerCountWithSameName FROM players WHERE gameID = :gameID AND name = :name");
    $stmt->execute(['gameID' => $gameID, 'name' => $username]);

    $playerCountWithSameName =  $stmt->fetchColumn();

    // $row = $result->fetch_assoc();
    // $playerCountWithSameName = $row['playerCountWithSameName'] ?? 0;
    return $playerCountWithSameName == 0;
}

function add_player_to_lobby(string $gameID, string $playername) {
    global $db;
    $sqlQuery = 'INSERT INTO players(gameID, name) VALUES (:gameID, :name)';

        $insertGame = $db->prepare($sqlQuery);
        $insertGame->execute([
            'gameID' => $gameID,
            'name' => $playername
        ]);
}


function stopRunning(array $gameData): bool
{
    global $db;

    # Games started 48h ago are deleted
    if (time() - $gameData['timestamp'] > 3600 * 24 * 2) {
        $sqlQuery = 'UPDATE games SET gameState = -1 WHERE _uuid = :_uuid';

        $updateGame = $db->prepare($sqlQuery);
        $updateGame->execute([
            '_uuid' => $gameData['_uuid'],
        ]);
        return true;
    } else
        return false;
}

function stopRunningAll(array $gamesData): array
{
    $rtnval = ['val' => true, 'active' => -1];
    $gamenb = -1;
    foreach ($gamesData as $gameData) {
        $gamenb++;
        if (!stopRunning($gameData)) {
            $rtnval = ['val' => false, 'active' => $gamenb];
        }
    }
    return $rtnval;
}

function gameStatus(int $id, string $gameid): array
{
    global $db;

    $gameStatement = $db->prepare('SELECT gameState FROM games WHERE _uuid = :_uuid AND gameID = :gameid');
    $gameStatement->execute(['_uuid' => $id, 'gameid' => $gameid]);
    $games = $gameStatement->fetchAll();

    $nbgame = count($games);
    if ($nbgame != 1) return ['found' => false];

    return [
        'found' => true,
        'game' => $games[0]
    ];
}