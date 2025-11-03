<?php include_once(__DIR__ . "/../../fcts/requests.php") ?>
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
            foreach (get_connected_players($_SESSION['gameID']) as $player) {
                ?>
                <li><?php echo $player["name"] ?></li>
                <?php
            }
        ?>
    </ul>
    <?php //if (!$foundmyself)  { // TODO: maybe reimplement this
        // include('../kicked.php'); ?>
        <!-- <script type="text/javascript"> 
        window.location.href="./"
        </script>  -->
    <?php //} ?>
    <script>
        let openeddropdown = sessionStorage.getItem("dropdownopened");
        if (openeddropdown) {
            getdropdown(openeddropdown)
        }
    </script>
</div>