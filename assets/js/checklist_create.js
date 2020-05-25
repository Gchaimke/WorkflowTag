var toggle;
var progress_status = "";
var chArray = [];
var scansArray = [];


$(document).ready(function () {
    toggle = true;
    var checkRows = $('.check_row');
    var selectRows = $('select.review');
    var scanRows = $('.scan_row');
    chArray = $('#input_data').val().split(",");
    scansArray = $('#input_scans').val().split(";").map(function (e) {
        return e.split(",");
    });
    checkRows.each(function () {
        if ($(this).find("input").prop('checked')) {
            $(this).find("input").after("<div class='badge badge-secondary check-lable'>" + chArray[$(this).find("input").attr('id')] + "</div>");
        }
    });

    selectRows.each(function () {
        if (chArray[this.id]) {
            $(this).val(chArray[this.id]);
        }
    });

    scanRows.each(function () {
        var id = $(this).closest('tr').attr('id');
        if (scansArray[id]) {
            $(this).find("input:eq(0)").val(scansArray[id][0]);
            $(this).find("input:eq(1)").val(scansArray[id][1]);
        } else {
            scansArray.splice(id, 0, ["", ""]);;
        }
    });

    chArray = chArray.slice(0, checkRows.length * 3);
    updateProgress();
});

function updateProgress() {
    var checkRows = $('.check_row').length;
    var checked = $("input:checkbox:checked").length;
    progress_status = 100 / (checkRows) * checked
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

$(".scans").change(function (e) {
    var id = $(this).closest('tr').attr('id');
    var sn = $(this).closest('tr').find("input:eq(0)").val();
    var rev = $(this).closest('tr').find("input:eq(1)").val();
    scansArray[id][0] = sn;
    scansArray[id][1] = rev;
    $('#input_scans').val(toString2d(scansArray));
});


function toString2d(arr) {
    var str = '';
    for (var row = 0; row < arr.length; row++) {
        str += arr[row].toString();
        str += ";";
    }
    return str
}

jQuery.extend(jQuery.expr[':'], {
    focusable: function (el, index, selector) {
        return $(el).is('a, button, :input, [tabindex]');
    }
});

$(document).on('keypress', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        // Get all focusable elements on the page
        var $canfocus = $(':focusable');
        var index = $canfocus.index(this) + 1;
        if (index >= $canfocus.length) index = 0;
        $canfocus.eq(index).focus();
    }
});

document.onkeydown = function (e) {

    if (e.ctrlKey && e.which == 83) {
        e.preventDefault();
        $(".saveData").submit();
    } else if (e.ctrlKey && e.which == 37) {
        e.preventDefault();
        var pathname = window.location.pathname.split("/");
        window.location.href = '/' + pathname[1] + "/" + pathname[2] + "/" + (parseInt(pathname[3]) - 1);
    } else if (e.ctrlKey && e.which == 39) {
        e.preventDefault();
        var pathname = window.location.pathname.split("/");
        window.location.href = '/' + pathname[1] + "/" + pathname[2] + "/" + (parseInt(pathname[3]) + 1);
    }
};

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