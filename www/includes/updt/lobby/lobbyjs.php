<script>
var webpage = `http://${window.location.hostname}/nokertu/www/join/<?php echo $_SESSION['gameID'] ?>`;
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