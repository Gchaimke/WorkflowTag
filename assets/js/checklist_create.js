var toggle;
var progress_status = "";
var chArray = [];


$(document).ready(function () {
    toggle = true;
    var rowCheckboxes = $('.check_row');
    var rowSelections = $('select.review');
    chArray = $('#input_data').val().split(",");
    rowCheckboxes.each(function () {
        if ($(this).find("input").prop('checked')) {
            $(this).find("input").after("<div class='badge badge-secondary check-lable'>" + chArray[$(this).find("input").attr('id')] + "</div>");
        }
    });

    rowSelections.each(function () {
        if (chArray[this.id]) {
            $(this).val(chArray[this.id]);
        }
    });
    chArray = chArray.slice(0, rowCheckboxes.length * 3);
    updateProgress();
});

function updateProgress() {
    var rowCheckboxes = $('.check_row').length;
    var checked = $("input:checkbox:checked").length;
    progress_status = 100 / (rowCheckboxes) * checked
    setProgress(progress_status);
}

function setProgress(p) {
    if (p <= 100) {
        $("#progress-bar").width(p + "%");
        $("#progress-bar").attr("aria-valuenow", p);
    } else {
        $("#progress-bar").width("100%");
        $("#progress-bar").attr("aria-valuenow", 100);
    }
}

function toggleOne(id, name) {
    if ($(event.target).is(":checked")) {
        $("#" + id).after("<div class='badge badge-secondary check-lable'>" + assembler + "</div>");
        chArray[id] = name;
    } else {
        $("#" + id + "+ div").remove();
        chArray[id] = '';
    }
    updateProgress();
}

function toggleQc(id, qc_name) {
    if (qc_name != '') {
        $("#" + id).after("<div class='badge badge-secondary check-lable'>" + qc_name + "</div>");
        chArray[id] = qc_name;
    } else {
        $("#" + id + "+ div").remove();
        chArray[id] = '';
    }
    updateProgress();
}

function selectOne(id, name) {
    if (name != '') {
        chArray[id] = name;
    } else {
        chArray[id] = '';
    }
    updateProgress();
}

$("input:checkbox.verify").click(function (e) {
    toggleOne(this.id, assembler);
    $('#input_data').val(chArray.toString());
    $('#input_progress').val(progress_status);
    var now = new Date();
    if ($(event.target).is(":checked")) {
        log += now.toLocaleString() + " " + assembler + " checked " + (parseInt(this.id) + 1) + ";";
    }
    $('#input_log').val(log);
});


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
    var pass = prompt(name + " please enter your Password.", "");
    $.post("/users/get_verify",
        {
            name: name,
            pass: pass
        },
        function (verify) {
            if (verify) {
                option.val(name);
                selectOne(id, name);
                $('#input_data').val(chArray.toString());
            } else {
                option.val("Select");
                selectOne(id, "Select");
                alert("Password error!");
                $('#input_data').val(chArray.toString());
            }
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