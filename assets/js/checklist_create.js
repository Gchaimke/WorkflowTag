var allTextLines = [];
var toggle;
var len = "";
var chArray = [];


$(document).ready(function () {
    toggle = true;
    setProgress(localStorage.getItem("progress"));
    if (JSON.parse(localStorage.getItem("chArray")) == null) {
        chArray = new Array(allTextLines.length);
    } else {
        chArray = JSON.parse(localStorage.getItem("chArray"));
    }
});

function getQCCode(id) {
    var code = prompt("Please enter QC Code", "QC Check");
    id = "#" + id;
    if (code == "1") {
        //$(id).attr("disabled", true);
        $(id).prop('checked', true);
        toggleOne();
    } else {
        //$(id).removeClass();
        //$(id).addClass("btn btn-danger");
        $(id).prop('checked', false);
        toggleOne();
    }
}

function saveData() {
    localStorage.setItem("chArray", JSON.stringify(chArray));
    chArray = JSON.parse(localStorage.getItem("chArray"));
    //console.log(chArray);
    localStorage.setItem("progress", len);
}

function updateProgress() {
    var rowCount = $('#checklist tr').length;
    var checked = $("input:checkbox:checked").length;
    len = 100 / (rowCount-1) * checked
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

$("#save").click(function () {
    $.post("save_page", {
        file: 'Production/' + pr + '/' + sn + '/' + sn + '.htm',
        page: document.getElementsByTagName('html')[0].innerHTML
    }).done(function (o) {
        console.log('save page.');
    });
});


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