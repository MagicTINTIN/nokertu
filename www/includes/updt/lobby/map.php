<?php 
$countrynamelist = [
    'black' => 'Jacata',
    'gray' => 'Ayacat',
    'red' => 'Reskat',
    'green' => 'Gatils',
    'yellow' => 'Ermao',
    'blue' => 'Stagat',
    'white' => 'Freezcat'
];

$countrycolorlist = [
    'black' => ["noire", "Black"],
    'gray' => ["grise", "Gray"],
    'red' => ["rouge", "Red"],
    'green' => ["verte", "Green"],
    'yellow' => ["jaune", "Yellow"],
    'blue' => ["bleue", "Blue"],
    'white' => ["blanche", "White"]
];


?>
<form id="countries" method="post">
    <img id="backgroundMapImg" class="countryImg" src="images/map/elements/base.jpg" />
    <?php 
    foreach ($countrylist as $cnb => $color) {
        if ($countryplayerlist[$cnb] != '') {
    ?>
        <img id="<?php echo $color ?>Img" class="countryImg" src="images/map/elements/<?php echo $color ?>/<?php echo (str_starts_with($countryplayerlist[$cnb], $_SESSION['nickname'] . ' (' )) ? "selected" : "taken" ?>.png" alt="Join <?php echo $color ?> team (<?php echo $countrynamelist[$color] ?>)" />
        <div class="playerMapDiv" id="<?php echo $color ?>PMDiv">
            <span id="<?php echo $color ?>Player" class="playerMap <?php echo $color ?>P"><?php echo $countryplayerlist[$cnb] ?></span>
        </div>
        <?php 
        } else {
        ?>
        <img id="<?php echo $color ?>Img" class="countryImg" src="images/map/elements/<?php echo $color ?>/base.png" alt="Join <?php echo $color ?> team (<?php echo $countrynamelist[$color] ?>)" />
        <!-- <div class="playerMapDiv" id="<?php echo $color ?>PMDiv">
            <span id="<?php echo $color ?>Player" class="playerMap <?php echo $color ?>P"><?php echo $color ?></span>
        </div> -->
        <?php 
        }
    }
    ?>
</form>