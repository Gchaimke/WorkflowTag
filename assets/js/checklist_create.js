var toggle;
var progress_status = "";
var chArray = [];


$(document).ready(function () {
    toggle = true;
    var rowCount = $('div.checkbox input:checkbox');
    $.each(rowCount, function (id, value) {
        if (value.checked) {
            chArray[id] = 1;
        } else {
            chArray[id] = 0;
        }
    });
    updateProgress();
});

function updateProgress() {
    var rowCount = $('#checklist tr').length;
    var checked = $("input:checkbox:checked").length;
    progress_status = 100 / (rowCount - 1) * checked
    $("#progress-bar").width(progress_status + "%");
    $("#progress-bar").attr("aria-valuenow", progress_status);
}

function setProgress(p) {
    $("#progress-bar").width(p + "%");
    $("#progress-bar").attr("aria-valuenow", p);
}

function toggleOne(id) {
    if ($(event.target).is(":checked")) {
        chArray[id] = 1;
    } else {
        chArray[id] = 0;
    }
    updateProgress();
}

function getQCCode(id) {
    id = "#" + id;
    var code = prompt("Please enter QC Code", "");
    $.post("/users/get_qc",
        {
            pass: code
        },
        function (data, status) {
            if (data) {
                $(id).prop('checked', true);
                toggleOne(id);
                $('#input_qc').val(data);
            } else {
                $(id).prop('checked', false);
                toggleOne(id);
                $('#input_qc').val("");
            }
        });
}

$("input:checkbox").click(function (e) {
    toggleOne(this.id);
    $('#input_data').val(chArray.toString());
    $('#input_progress').val(progress_status)
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