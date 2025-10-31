<?php 
$msgtxt = [
    ["Non", "No"],
    ["Oui", "Yes"]
];

if(isset($errorMessage)) : ?>
    <div id="errorMsg" role="alert" class="msg errorMsg errorAnim">
        <span></span>
        <span><?php echo $errorMessage; ?></span>
        <span onclick="deleteMsg('error')" ontouchstart="deleteMsg('error')" class="closeMsg">X</span>
    </div>
<?php endif;
if(isset($infoMessage)) : ?>
    <div id="infoMsg" class="msg infoMsg infoAnim">
        <span></span>
        <span><?php echo $infoMessage; ?></span>
        <span onclick="deleteMsg('info')" ontouchstart="deleteMsg('info')" class="closeMsg">X</span>
    </div>
<?php endif;
if(isset($confirmData)) :?>
    <div id="confirmMsg">
        <span><?php echo $confirmData['question']; ?></span>
        <div id="confirmbuttons">
            <form method="post" id="confirmno">
                <?php 
                foreach ($confirmData['no'] as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value' />";
                }
                ?>
                <input onclick="deleteMsg('confirm')" type="submit" name="confirmnosub" class="nobtn" value="<?php echo $msgtxt[0][$lng] ?>" />
            </form>
            <form method="post" id="confirmyes">
                <?php 
                foreach ($confirmData['yes'] as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value' />";
                }
                ?>
                <input onclick="deleteMsg('confirm')" type="submit" name="confirmyessub" class="yesbtn" value="<?php echo $msgtxt[1][$lng] ?>" />
            </form>
        </div>
    </div>
<?php endif; ?>

<div id="jserrorMsg" role="alert" class="errorMsg msg">
    <span></span>
    <span id="jserrorcontent"></span>
    <span onclick="deleteMsg('jserror')" ontouchstart="deleteMsg('jserror')" class="closeMsg">X</span>
</div>
            
<div id="jsinfoMsg" class="infoMsg msg">
    <span></span>
    <span id="jsinfocontent"></span>
    <span onclick="deleteMsg('jsinfo')" ontouchstart="deleteMsg('jsinfo')" class="closeMsg">X</span>
</div>

<div id="jswarningMsg" class="warningMsg msg">
    <span></span>
    <span id="jswarningcontent"></span>
    <span onclick="deleteMsg('jswarning')" ontouchstart="deleteMsg('jswarning')" class="closeMsg">X</span>
</div>
            
<div id="jsconfirmMsg">
    <span id="jsconfirmcontent"></span>
    <div id="confirmbr"></div>
    <div id="confirmbuttons">
        <span onclick="confirmAnswer(false)" class="spanbtn nobtn"><?php echo $msgtxt[0][$lng] ?></span>
        <span onclick="confirmAnswer(true)"  class="spanbtn yesbtn"><?php echo $msgtxt[1][$lng] ?></span>
    </div>
</div>
