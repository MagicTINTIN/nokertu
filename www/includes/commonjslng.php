<?php
    $commonjslngtxt = [
        [ "Votre navigateur a l'air de bloquer le JavaScript<br>Essayez en désactivant votre bloqueur de scripts","Your web browser seems to block JavaScript<br>Try disabling your script blocker"],
        [ "EN", "FR" ],
        [ "en", "fr" ],
    ];
?>

<div id="nojs">
    <div class="bfcodediv">
        <p>Hmmm....<br><br>
        <?php echo $commonjslngtxt[0][$lng] ?>
        </p>
    </div>
</div>
<div id="hellotouch">
    <div class="bfcodediv">
        <p id="touchmsg">TOUCH !</p>
    </div>
</div>
<form method="post" id="languageselection">
    <?php 
    foreach ($_POST as $key => $value) {
        echo "<input type='hidden' name='$key' value='$value' />";
    }
    ?>
    <input type="hidden" name="l" value="<?php echo $commonjslngtxt[2][$lng] ?>" />
    <input type="submit" name="language" value="<?php echo $commonjslngtxt[1][$lng] ?>" />
</form>