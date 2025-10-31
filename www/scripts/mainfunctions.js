document.getElementById("nojs").style.display = "none";
document.getElementById("hellotouch").style.display = "none";
let activedropdown = false;

String.prototype.replaceAt = function (index, replacement) {
    return this.substring(0, index) + replacement + this.substring(index, this.length).substring(1); //replacement.length
}

String.prototype.biReplaceAt = function (index1, replacement1, index2, replacement2) {
    if (index1 < index2)
        return this.substring(0, index1) + replacement1 + this.substring(index1, index2).substring(1) + replacement2 + this.substring(index2, this.length).substring(1); //replacement.length
    else
        return this.substring(0, index2) + replacement2 + this.substring(index2, index1).substring(1) + replacement1 + this.substring(index1, this.length).substring(1); //replacement.length
}

function deleteMsg(type) {
    const obj = document.getElementById(`${type}Msg`);
    if (obj) {
        obj.style.transform = "scale(0) rotate(45deg)";
        obj.style.margin = "0px";
    }
    else return 1
}

function copytcb(tocopy) {
    const storage = document.createElement('textarea');
    try {
        storage.value = tocopy;
        document.body.appendChild(storage);

        storage.select();
        storage.setSelectionRange(0, 99999);
        document.execCommand('copy');

        logger('Link copied to clipboard', 2);
        return 0;

    } catch (err) {
        errLogger('Failed to copy: ', err, 2);
        return 1;
    }
    finally {
        document.body.removeChild(storage);
    }
}

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function getdropdown(name, fromclick = false) {
    closalldropdowns();
    let todd = document.getElementById("dd" + name)
    if (todd && (!fromclick || !sessionStorage.getItem("dropdownopened") || sessionStorage.getItem("dropdownopened") != name)) {
        todd.classList.toggle("show");
        if (fromclick)
            todd.classList.toggle("showanimation");
        sessionStorage.setItem("dropdownopened", name);
    }
    else {
        sessionStorage.removeItem("dropdownopened")
    }
}

function closalldropdowns() {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
        if (openDropdown.classList.contains('showanimation')) {
            openDropdown.classList.remove('showanimation');
        }
    }
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    activedropdown = !activedropdown && !event.target.matches('.dropbtn');
    if (activedropdown) {
        sessionStorage.removeItem("dropdownopened")
        closalldropdowns();
    }
}

function sendtoserver(url, postdata, from = "test") {
    $.ajax({
        method: "POST",
        url: url,
        data: postdata
    }).done(function (msg) {

        if (from == "test") console.log(msg);
        else if (from == "confirm") {
            let confirmResult = JSON.parse(msg);

            if (confirmResult && confirmResult.length > 2 && confirmResult[0]) {
                if (confirmResult[1] == 'kick') {
                    showMessage(confirmResult[2], confirmResult[3]);
                    sendGame(confirmResult[4], 'playerQuit');
                }
            }
        }
    });
}

// Confirm message

let urlconfirm = "";
let yesconfirm = {};
let noconfirm = {};

jsconfirm = document.getElementById("jsconfirmMsg");
jsconfirm.style.display = "none";
jsconfirm.style.transform = "scale(0)";
confirmcontent = document.getElementById("jsconfirmcontent");

function showConfirm(message, posturl, yesdata, nodata) {
    confirmcontent.innerText = message;
    urlconfirm = posturl;
    yesconfirm = yesdata;
    noconfirm = nodata;

    jsconfirm.style.display = "flex";
    jsconfirm.style.transform = "scale(1) rotate(0deg)";
    animateCSS("jsconfirmMsg", "infoAnim", 800);
}

function confirmAnswer(value) {
    deleteMsg('jsconfirm');
    let datatosend = value ? yesconfirm : noconfirm;

    sendtoserver(urlconfirm, datatosend, "confirm");
}

// Message animations and functions

jsinfo = document.getElementById("jsinfoMsg");
jsinfo.style.display = "none";
jsinfo.style.transform = "scale(0)";
infocontent = document.getElementById("jsinfocontent");

jserror = document.getElementById("jserrorMsg");
jserror.style.display = "none";
jserror.style.transform = "scale(0)";
errorcontent = document.getElementById("jserrorcontent");

jswarning = document.getElementById("jswarningMsg");
jswarning.style.display = "none";
jswarning.style.transform = "scale(0)";
warningcontent = document.getElementById("jswarningcontent");

function animateCSS(objectid, classname, duration) {
    let objecttoanim = document.getElementById(objectid)
    let saveattributes = objecttoanim.getAttribute("class")
    objecttoanim.setAttribute("class", saveattributes + " " + classname);
    setTimeout(function () {
        objecttoanim.setAttribute("class", saveattributes);
    }, duration);
}

function showInfo(message) {
    infocontent.innerText = message;
    jsinfo.style.display = "flex";
    jsinfo.style.transform = "scale(1) rotate(0deg)";
    animateCSS("jsinfoMsg", "infoAnim", 800);
}

function showError(message) {
    errorcontent.innerText = message;
    jserror.style.display = "flex";
    jserror.style.transform = "scale(1) rotate(0deg)";
    animateCSS("jserrorMsg", "errorAnim", 800);
}

function showWarning(message) {
    warningcontent.innerText = message;
    jswarning.style.display = "flex";
    jswarning.style.transform = "scale(1) rotate(0deg)";
    animateCSS("jswarningMsg", "warningAnim", 800);
}

function showMessage(type, message) {
    if (type == 0)
        showInfo(message);
    else if (type == 1)
        showWarning(message);
    else if (type == 2)
        showError(message);
}