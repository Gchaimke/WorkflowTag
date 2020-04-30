var allTextLines = [];
var toggle;
var len = "";
var chArray = [];


$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/Templates/"+pr+".csv",
        dataType: "text",
        success: function (data) { processData(data); }
    });

    toggle = true;
    setProgress(localStorage.getItem("progress"));
    $("#project").append(pr);
    $("#sn").append(sn);

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    $("#date").append(dd + "/" + mm + "/" + yyyy);
});

function GetURLParameter(sParam) {
    //?pr=Flex2&sn=FL-0105-001
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

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
    var checked = $("input:checkbox:checked").length;
    len = 100 / (allTextLines.length - 1) * checked
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

function processData(allText) {
    allTextLines = allText.split(/\r\n|\n/);
    var headers = allTextLines[0].split(';');
    var table = "";
    var prefix = "1.0";
    var verify = "";
    var checked = "";
    var onClick = "";
    if (JSON.parse(localStorage.getItem("chArray")) == null) {
        chArray = new Array(allTextLines.length);
    } else {
        chArray = JSON.parse(localStorage.getItem("chArray"));
    }
    table = '<table   class="table"> \
            <thead class="thead-dark"><tr>\
            <th scope="col" onclick="saveData()">#</th>\
            <th id="result"  scope="col">'+ headers[0] + '</th>\
            <th scope="col" onclick="toggleAllCheckboxs()">'+ headers[1] + '</th>\
            </tr></thead><tbody>';
    for (var i = 1; i < allTextLines.length; i++) {
        checked = "";
        if (chArray[i])
            checked = "Checked";
        if (i >= 10)
            prefix = '1.'
        var data = allTextLines[i].split(';');
        if (data[1] == "QC") {
            onClick = ' onclick="getQCCode(this.id)"';
        } else {
            onClick = ' onclick="toggleOne()"';
        }
        verify = '<div class="checkbox">\
                    <input type="checkbox"  id="check_'+ i + '" name="check' + i + '" ' +
            onClick + ' ' + checked + '></div>';
        table += '<tr><th scope="row">' + prefix + i + '</th>';
        table += '<td class="description">' + data[0] + '</td>';
        table += '<td onclick="restore()">' + verify + '</td></tr>';
    }
    table += '</tbody></table>';
    $(workTable).append(table);
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

$("#save").click(function () {
    $.post("save_page", {
        file: 'Production/' + pr + '/' + sn + '/' + sn + '.htm',
        page: document.getElementsByTagName('html')[0].innerHTML
    }).done(function (o) {
        console.log('save page.');
    });
});