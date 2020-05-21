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

function toggleQc(id, qc_name) {
    if (qc_name != '') {
        chArray[id] = 1;

    } else {
        chArray[id] = 0;
    }
    updateProgress();
}

$("input:checkbox.qc").click(function (e) {
    event.preventDefault();
    id = this.id;
    var code = prompt("Please enter QC Code", "");
    $.post("/users/get_qc",
        {
            pass: code
        },
        function (qc_name) {
            if (qc_name) {
                $("#" + id).prop('checked', true);
                toggleQc(id, qc_name);
                $('#input_qc').val(qc_name);
                var now = new Date();
                log += now.toLocaleString() + " QC " + qc_name + " checked " + (parseInt(id) + 1) + ";";
                $('#input_log').val(log);
                $('#input_data').val(chArray.toString());
                $('#input_progress').val(progress_status);
            } else {
                $("#" + id).prop('checked', false);
                toggleQc(id, qc_name);
                $('#input_data').val(chArray.toString());
                $('#input_progress').val(progress_status);
            }
        });

});

$("select.review").change(function (e) {
    event.preventDefault();
    id = this.id;
    var option = $(this).children("option:selected");
    var name = option.val();
    var pass = prompt(name+" please enter your Password.", "");
    $.post("/users/get_verify",
        {
            name: name,
            pass: pass
        },
        function (verify) {
            if (verify) {
                option.prop("selected", true);
            } else {
                alert("Password error!")
                option.prop("selected", false);
            }
        });

});

$("input:checkbox.verify").click(function (e) {
    toggleOne(this.id);
    $('#input_data').val(chArray.toString());
    $('#input_progress').val(progress_status);
    var now = new Date();
    if ($(event.target).is(":checked")) {
        log += now.toLocaleString() + " " + assembler + " checked " + (parseInt(this.id) + 1) + ";";
    }
    $('#input_log').val(log);
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