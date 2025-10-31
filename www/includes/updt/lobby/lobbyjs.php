<script>
var webpage = `http://${window.location.hostname}/Nokertu/<?php echo $_SESSION['gameID'] ?>`;
// var qrc = new QRCode(document.getElementById("qrcode"), webpage);
// qrlink = document.getElementById("qrlink");
qrlink.innerText = webpage;

let timer1, timer2, timer3;
    
function copyAnim(success) {
    let color = (success) ? 'green' : 'red';
    let msg = (success) ? "<?php echo $updtLBtexts[7][$lng] ?>" : "<?php echo $updtLBtexts[8][$lng] ?>";
    qrlink.style.width = `${qrlink.getBoundingClientRect().width - 10}px`;
    qrlink.style.height = `${qrlink.getBoundingClientRect().height - 10}px`;
    qrlink.style.textAlign = "center";
    qrlink.style.verticalAlign = "middle";
    qrlink.style.backgroundColor = `var(--${color})`;
    qrlink.innerText = msg;

    timer1 = setTimeout(() => {
        qrlink.style.backgroundColor = `var(--dark-${color})`;
    }, 400);
    timer2 = setTimeout(() => {
        qrlink.style.backgroundColor = "";
    }, 700);
    timer3 = setTimeout(() => {
        document.getElementById("qrlink").innerText = webpage;
        qrlink.style.textAlign = "center";
        qrlink.style.verticalAlign = "middle";
        qrlink.style.height = "auto"
        qrlink.style.width = "calc(100% - 10px)"
    }, 2000);
}

function cplink() {

    let copiedstatus = copytcb(webpage);

    clearTimeout(timer1);
    clearTimeout(timer2);
    clearTimeout(timer3);
    // copy("ntm");
    // let copiedstatus = copytcb(webpage);
    copyAnim(!copiedstatus);
}

</script>


<script>
$("#backgroundMapImg").on("click touchstart  touchend", function () {
    <?php if ($DEBUGMODE % 7 == 0) echo 'console.log("Background image clicked!");' ?>
    
    $.ajax({
            method: "POST",
            url: "includes/updt/lobby/team.php",
            data: { color: "none"}
        }).done(function( msg ) { 
                <?php if ($DEBUGMODE % 7 == 0) echo 'console.log( "Received: " + msg );' ?>
                sendGame('<?php echo $_SESSION['ID'] . '|'. $_SESSION['gameID'] ?>', 'playerTeam');
        });
});

<?php 
$countrylistforjs = [
    'black' => 'end',
    'gray' => 'black',
    'red' => 'gray',
    'green' => 'red',
    'yellow' => 'green',
    'blue' => 'yellow',
    'white' => 'blue'
];

?>

fullyinitialised = false;
globalPX = 0, globalPY = 0, globalCX = 0, globalCY = 0;

function resetPE() {
    <?php foreach ( $countrylistforjs as $color => $name) { ?>
    document.getElementById('<?php echo $color ?>Img').style.pointerEvents = "auto";
    <?php } ?>
}

function resetImages() {
    <?php foreach ( $countrylistforjs as $color => $name) { ?>
    document.getElementById('<?php echo $color ?>Img').src = 'images/map/elements/<?php echo $color ?>/base.png';
    <?php } ?>
}

function hellotouch() {
    setTimeout(() => {
        document.getElementById("hellotouch").style.display = "block";
    }, 200);
    setTimeout(() => {
        document.getElementById("hellotouch").style.display = "none";
    }, 700);
}

<?php foreach ( $countrylistforjs as $color => $name) { ?>

var ctx<?php echo $color ?>;

$('#<?php echo $color ?>Img').on("touchstart touchend click", event => <?php echo $color ?>ImgClickEvent(event));

function <?php echo $color ?>ImgClickEvent(event) {

    // hellotouch();

    if(event.handled === false) return document.getElementById("touchmsg").innerHTML = "NOT HANDLED"
    event.stopPropagation();
    event.preventDefault();
    event.handled = true;

    <?php if ($DEBUGMODE % 11 == 0) echo "console.log('Clicked on : ', object$color);" ?>
    
    var eventPX, eventPY, eventCX, eventCY;
    if (event.pageX == 0 && event.pageY == 0 && event.clientX == 0 && event.clientY == 0) {
        eventPX = globalPX;
        eventPY = globalPY;
        eventCX = globalCX;
        eventCY = globalCY;
    }
    else {
        eventPX = globalPX = event.pageX;
        eventPY = globalPY = event.pageY;
        eventCX = globalCX = event.clientX;
        eventCY = globalCY = event.clientY;
    }

    // document.getElementById("touchmsg").innerHTML = "waiting <?php echo $color ?>"
    <?php if ($DEBUGMODE % 11 == 0) echo "console.log(eventCX, eventCY, event.clientX, event.clientY);" ?>

    var x = eventPX - object<?php echo $color ?>.offsetLeft,
        y = eventPY - object<?php echo $color ?>.offsetTop;
        
    var alpha = ctx<?php echo $color ?>.getImageData(x, y, 1, 1).data[3];
    // document.getElementById("touchmsg").innerHTML = "got data <?php echo $color ?>"

    if (alpha === 0) {
        // document.getElementById("touchmsg").innerHTML = "C pas <?php echo $color ?>"
        object<?php echo $color ?>.style.pointerEvents = "none";
        
        document.elementFromPoint(eventCX, eventCY).click();
        <?php if ($color == "black") { ?>resetPE();<?php } ?>
        
    } else {
        // document.getElementById("touchmsg").innerHTML = "C <?php echo $color ?>"
        <?php if ($DEBUGMODE % 5 == 0) { ?> console.log(`<?php echo $color ?> clicked!`); <?php } ?>
        resetPE();

        $.ajax({
            method: "POST",
            url: "includes/updt/lobby/team.php",
            data: { color: "<?php echo $color ?>"} 
        }).done(function( msg ) { 
            res = JSON.parse(msg);
            <?php if ($DEBUGMODE % 7 == 0) echo 'console.log( "Received: ",  res);' ?>
            if (res[0] == 1) 
                sendGame('<?php echo $_SESSION['ID'] . '|'. $_SESSION['gameID'] ?>', 'playerTeam');
        });
    }
};

<?php
}
?>

function setCtx() {
<?php foreach ( array_reverse($countrylistforjs) as $color => $name) { ?>
    ////////////////// SET <?php echo $color ?> \\\\\\\\\\\\\\\\\\
    ctx<?php echo $color ?> = document.createElement("canvas").getContext("2d", { willReadFrequently: true });
    object<?php echo $color ?> = document.getElementById('<?php echo $color ?>Img');
    

<?php } ?>
    setCtxDraw()
}

function setCtxDraw() {
    <?php if ($DEBUGMODE % 13 == 0) echo 'console.log("Before resize", ctxblack.canvas.width, ctxblack.canvas.height);' ?>
<?php foreach ( array_reverse($countrylistforjs) as $color => $name) { ?>
    ////////////////// DRAW <?php echo $color ?> \\\\\\\\\\\\\\\\\\
    ctx<?php echo $color ?>.clearRect(
        0, 0, 
        ctx<?php echo $color ?>.canvas.width, ctx<?php echo $color ?>.canvas.height
        );

    var w<?php echo $color ?> = ctx<?php echo $color ?>.canvas.width = object<?php echo $color ?>.width,
        h<?php echo $color ?> = ctx<?php echo $color ?>.canvas.height = object<?php echo $color ?>.height;

    

    ctx<?php echo $color ?>.drawImage(object<?php echo $color ?>, 0, 0, w<?php echo $color ?>, h<?php echo $color ?>);


<?php } ?>
    <?php if ($DEBUGMODE % 13 == 0) echo 'console.log("After resize", ctxblack.canvas.width, ctxblack.canvas.height);' ?>;
    fullyinitialised = true;
}

window.onresize = function(event) {
    <?php if ($DEBUGMODE % 13 == 0) echo 'console.log("Rewriting canvas");' ?>
    setCtxDraw();
};

Promise.all(Array.from(document.images).map(img => {
    if (img.complete)
        return Promise.resolve(img.naturalHeight !== 0);
    return new Promise(resolve => {
        img.addEventListener('load', () => resolve(true));
        img.addEventListener('error', () => resolve(false));
    });
})).then(results => {
    if (results.every(res => res)) {

        <?php if ($DEBUGMODE % 13 == 0) echo "console.log('All images loaded successfully');" ?>
        setCtx();
    }
    else {
        <?php if ($DEBUGMODE % 13 == 0) echo "console.log('Some images failed to load, all finished loading\nIt might be a source of problem');" ?>
        setCtx();
    }
});

</script>