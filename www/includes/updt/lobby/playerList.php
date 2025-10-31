<div id="lbPlayerList">
    <div id="sharelink">
        <div id="sharetexts">
        <h3 id="qrtitle"><?php echo $updtLBtexts[5][$lng] ?></h3>
        <h3 id="qrtitle"><?php echo $_SESSION['gameID'] ?></h3>
        <p id="qrlink" onclick="cplink()" ontouchstart="cplink()" title="<?php $updtLBtexts[6][$lng] ?>"></p>
        </div>
    </div>
    <h3><?php echo $updtLBtexts[1][$lng] ?></h3>
    <ul>
        <?php
            if (strlen($_SESSION['game']['playerList']) > 0) {
                $playersArray = explode('┇', $gameData['game']['playerList']);
                $nb = 0;
                $foundmyself = false;

                foreach ($playersArray as $playerobj) {
                    $nb++;
                    $playerArr = explode('┊', $playerobj);

                    $onclickpseudo = '';

                    if ( $playerArr[0] != $_SESSION['nickname'] && (
                        (isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID']) ||
                        isset($countrylist[$playerArr[1]])
                        ))
                        $onclickpseudo = 'onclick="getdropdown(\'' . $playerArr[0] . '\', true)"';
            
                    if (isset($countrylist[$playerArr[1]]))
                        echo '<li ' . $onclickpseudo . ' class="li' . ($nb % 2) . ' ' . $countrylist[$playerArr[1]] . 'ChoosenTeam dropbtn">';
                    else
                        echo '<li ' . $onclickpseudo . ' class="li' . ($nb % 2) . ' dropbtn">';
                    
                    echo '<span class="listnumber">' . $nb . '</span>' . $playerArr[0];

                    if ($playerArr[0] == $_SESSION['nickname']) {
                        echo '<span class="listyou">' . $updtLBtexts[3][$lng] . '<span></li>';
                        $foundmyself = true;
                    }
                    elseif ((isset($_SESSION['gameOwner']) && $_SESSION['gameOwner'] == $_SESSION['ID'])) {
                        echo '</li>
                            <div id="dd' . $playerArr[0] . '" class="dropdown-content">';
                        if (isset($countrylist[$playerArr[1]]) && isset($countrynamelist[$countrylist[$playerArr[1]]])) {
                            echo '<span class="ddelement ' . $countrylist[$playerArr[1]] . 'cardinfo cardinfo">' 
                                . sprintf($updtLBtexts[13][$lng],
                                $countrynamelist[$countrylist[$playerArr[1]]], 
                                $countrycolorlist[$countrylist[$playerArr[1]]][$lng])
                                . '</span>
                                <span onclick="askchoose(\'' . $playerArr[0] .'\')" class="ddelement ddbtn">' 
                                . $updtLBtexts[12][$lng] . '</span>';
                        }
                        echo '<span onclick="showConfirm(\'' 
                                . sprintf($updtLBtexts[10][$lng], $playerArr[0]) 
                                . '\', \'includes/game/kick.php\', {kickplayer:\'true\',kickpseudo:\'' 
                                . $playerArr[0] . '\'}, {})" class="ddelement ddbtn">' 
                                . $updtLBtexts[9][$lng] . $playerArr[0] . '</span>
                            <span onclick="pingchoose(\'' . $playerArr[0] .'\')" class="ddelement ddbtn">' 
                                . $updtLBtexts[11][$lng] . $playerArr[0] . '</span>';
                            
                        echo '</div>';
                    }
                    elseif (isset($countrylist[$playerArr[1]]) && isset($countrynamelist[$countrylist[$playerArr[1]]])) {
                        echo '</li>
                        <div id="dd' . $playerArr[0] . '" class="dropdown-content">
                            <span class="ddelement ' . $countrylist[$playerArr[1]] . 'cardinfo cardinfo">' 
                                . sprintf($updtLBtexts[13][$lng],
                                $countrynamelist[$countrylist[$playerArr[1]]], 
                                $countrycolorlist[$countrylist[$playerArr[1]]][$lng])
                                . '</span>
                            <span onclick="askchoose(\'' . $playerArr[0] .'\')" class="ddelement ddbtn">' 
                                . $updtLBtexts[12][$lng] . '</span>
                        </div>';
                    }
                    else 
                        echo '</li>';
                    
                    
                }
            }
        ?>
    </ul>
    <?php if (!$foundmyself)  {
        include('../kicked.php'); ?>
        <script type="text/javascript"> 
        window.location.href="./" 
        </script> 
    <?php } ?>
    <script>
        let openeddropdown = sessionStorage.getItem("dropdownopened");
        if (openeddropdown) {
            getdropdown(openeddropdown)
        }
    </script>
</div>