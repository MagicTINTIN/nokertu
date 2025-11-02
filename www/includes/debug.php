<?php 
$DEBUGMODE = 2*3*5;
const DEBUG_ALL = 0; 
const DEBUG_NOTHING = 1;
const DEBUG_TMP = 2;
const DEBUG_1 = 3;
const DEBUG_WEBSOCKET = 5;
// const DEBUG_WEBSOCKET = 0;

function debug_mode($mode) : bool {
    global $DEBUGMODE;
    return $DEBUGMODE % $mode == 0;
}

/* Set debug mode settings
 * 0 = all messages
 * 1 = no message at all
 * 
 * Ex: if you want both temporary and debug data infos set $DEBUGMODE to 2×3=6
 * 
 * 2   = Temporary debug data
 * 3   = Debug mode info
 * 5   = Websocket data
 * 7   = Lobby selection
 * 11  = Pointer events data
 * 13  = Canvas info
 * 
 * 
 * 
 */

?>