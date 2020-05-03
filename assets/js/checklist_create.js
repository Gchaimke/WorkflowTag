var allTextLines = [];
var toggle;
var len = "";
var chArray = [];


$(document).ready(function () {
    toggle = true;
    document.cookie = 'chArray =';
    document.cookie = 'progress =';
    CountRows();
    setProgress(localStorage.getItem("progress"));
    if (JSON.parse(localStorage.getItem("chArray")) == null) {
        chArray = [];
        for (var i = 0; i < CountRows(); i++) {
            chArray[i] = 0;
        }
    } else {
        chArray = JSON.parse(localStorage.getItem("chArray"));
    }
});

function getQCCode(id) {
    var code = prompt("Please enter QC Code", "QC Check");
    id = "#" + id;
    if (code == "1") {
        $(id).prop('checked', true);
        toggleOne();
    } else {
        $(id).prop('checked', false);
        toggleOne();
    }
}

function saveData() {
    localStorage.setItem("chArray", JSON.stringify(chArray));
    chArray = JSON.parse(localStorage.getItem("chArray"));
    //console.log(chArray);
    localStorage.setItem("progress", len);
    document.cookie = 'chArray ="' + chArray + '"';
    document.cookie = 'progress ="' + len + '"';
}

function updateProgress() {
    var rowCount = $('#checklist tr').length;
    var checked = $("input:checkbox:checked").length;
    len = 100 / (rowCount - 1) * checked
    $("#progress-bar").width(len + "%");
    $("#progress-bar").attr("aria-valuenow", len);
}

function setProgress(p) {
    $("#progress-bar").width(p + "%");
    $("#progress-bar").attr("aria-valuenow", p);
}

function toggleOne(item, index, arr) {
    if (event.target.id != "") {
        id = event.target.id.split("_");
    } else {
        id = 0;
    }

    if ($(event.target).is(":checked") || !toggle) {
        chArray[id[1]] = 1;
        chArray[index] = 1;
    } else {
        chArray[id[1]] = 0;
        chArray[index] = 0;
    }
    updateProgress();
    saveData();
}

function toggleAllCheckboxs() {
    $("[id^=check]").prop('checked', toggle);
    if (toggle) {
        toggle = false;
    } else {
        toggle = true;
    }
    chArray.forEach(toggleOne);
}

function CountRows() {
    var totalRowCount = 0;
    var table = document.getElementById("checklist");
    var rows = table.getElementsByTagName("tr")
    for (var i = 0; i < rows.length; i++) {
        totalRowCount++;
    }
    document.cookie = 'rowsJS ="' + totalRowCount + '"';
    return totalRowCount;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function centerLoginBox() {
    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1; // Detect Android devices
    if (isAndroid) {
        //window.orientation is different for iOS and Android
        if (window.orientation == 0 || window.orientation == 180) { //Landscape Mode
            $('#loginbox').css('margin-top', '20%');
        }
        else if (window.orientation == 90 || window.orientation == -90) { //Portrait Mode
            $('#loginbox').css('margin-top', '40%');
        }
    }
    else {
        if (window.orientation == 90 || window.orientation == -90) { //Landscape Mode
            $('#loginbox').css('margin-top', '20%');
        }
        else if (window.orientation == 0 || window.orientation == 180) { //Portrait Mode
            $('#loginbox').css('margin-top', '40%');
        }
    }
}