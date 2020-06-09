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

function toggleOne(id) {
    if ($(event.target).is(":checked")) {
        $("#" + id).after("<div class='badge badge-secondary check-lable'>" + assembler + "</div>");
        chArray[id] = assembler;
    } else {
        $("#" + id + "+ div").remove();
        chArray[id] = '';
    }
    updateProgress();
}

function toggleAll() {
    $('.verify').each(function () {
        $("#" + this.id).after("<div class='badge badge-secondary check-lable'>" + assembler + "</div>");
        chArray[this.id] = assembler;
        log += getDateTime() + " " + assembler + " checked " + $(this).closest("tr").find('th').text() + ";";
        $('#input_log').val(log);
    });
    $('.verify').prop('checked', true);
    updateProgress();
    $('#input_data').val(chArray.toString());
    $('#input_progress').val(progress_status);

}

function toggleQc(id, qc_name) {
    if (qc_name != '') {
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
    toggleOne(this.id);
    $('#input_data').val(chArray.toString());
    $('#input_progress').val(progress_status);
    if ($(event.target).is(":checked")) {
        log += getDateTime() + assembler + " checked " + $(this).closest("tr").find('th').text() + ";";
    }
    $('#input_log').val(log);
});


$("input:checkbox.qc").click(function (e) {
    event.preventDefault();
    id = this.id;
    curent_th = $(this).closest("tr").find('th').text();
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
                log += getDateTime() + " QC " + qc_name + " checked " + curent_th + ";";
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
    curent_th = $(this).closest("tr").find('th').text();
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
                toggleQc(id, name);
                $('#input_qc').val(name);
                log += getDateTime() + " QC " + name + " checked " + curent_th + ";";
                $('#input_log').val(log);
                $('#input_data').val(chArray.toString());
            } else {
                option.val('Select');
                toggleQc(id, "Select");
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

$('#result').click(function () {
    //alert(getDateTime());
});

function getDateTime() {
    var now = new Date();
    const ye = new Intl.DateTimeFormat('en', { year: '2-digit' }).format(now);
    const mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(now);
    const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(now);
    const H = now.getHours();
    const M = now.getMinutes();
    return `${da}/${mo}/${ye} ${H}:${M}`;
}

function print2PDF(url, cookie) {
    //alert("Printing "+id);   
    $.post("/production/save_page2pdf",
        {
            url: url,
            cookie: cookie
        },
        function (respond) {
            if (respond) {
                alert(respond);
            } else {
                alert("no respond");
            }
        });
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

//KEYBOARD BIDINGS START
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
    var pathname = window.location.pathname.split("/");
    if (e.ctrlKey && e.which == 83) { //ctrl + S
        e.preventDefault();
        $(".saveData").submit();
    } else if (e.ctrlKey && e.which == 37) { //ctrl + <-
        e.preventDefault();
        window.location.href = '/' + pathname[1] + "/" + pathname[2] + "/" + (parseInt(pathname[3]) - 1);
    } else if (e.ctrlKey && e.which == 39) { //ctrl + ->
        e.preventDefault();
        window.location.href = '/' + pathname[1] + "/" + pathname[2] + "/" + (parseInt(pathname[3]) + 1);
    } else if (e.ctrlKey && e.which == 81) { //ctrl+Q
        e.preventDefault();
        //print2PDF(window.location.href,ci_session);
        toggleAll();
    } else if (e.which == 40) {
        e.preventDefault();
        var focused = $(':focus')
        var id = focused.parent().parent().attr('id');
        if (id >= 0) {
            var id = parseInt(id) + 1;
        }
        $('tr[id^=' + id + '] input:eq(0)').focus();
    } else if (e.which == 38) {
        e.preventDefault();
        var id = $(':focus').parent().parent().attr('id');
        if (id >= 0) {
            id = parseInt(id) - 1;
        }
        $('tr[id^=' + id + '] input:eq(0)').focus();
    }
};