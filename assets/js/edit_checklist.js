var toggle = true;
var progress_status = "";
var checkRows = $('.check_row');
var inputRows = $('input.input');
var selectRows = $('select.review');
var AllCheckRows = $('.verify').length + $('.review').length;
var AllChecked = $("input:checkbox:checked").length + $('.review option:selected[value!="Select"]').length;
var chArray = checklist_data.split(",");

$(document).ready(function () {
    $("#picrures_count").val(photoCount);
    //Get checked rows and add Name after
    checkRows.each(function () {
        if ($(this).find("input").prop('checked')) {
            assembler_name = chArray[$(this).find("input").attr('id')];
            if (assembler_name.substring(0, 1) == "!") {
                $(this).find("input").prop("indeterminate", true).css("background-color", "#ff9595");
                assembler_name = assembler_name.substring(1)
            }
            $(this).find("input").after("<div class='badge badge-secondary check-lable'>" + assembler_name + "</div>");
        }
    });

    inputRows.each(function () {
        $(this).val(chArray[this.id]);
    });

    selectRows.each(function () {
        if (chArray[this.id]) {
            $(this).val(chArray[this.id]);
        } else {
            $(this).val('Select');
        }
    });
    updateProgress();
});

function updateProgress() {
    AllCheckRows = $('.verify').length + $('.review').length + $('.input_row').length;
    AllChecked = $("input:checkbox:checked").length
        + $('.review option:selected[value!="Select"]').length
        + $('input.input').filter(function () { return $(this).val(); }).length;
    progress_status = 100 / (AllCheckRows) * AllChecked
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

    $('#input_progress').val(progress_status);
}

function toggleOne(id) {
    if ($(event.target).is(":checked")) {
        $("#" + id).after("<div class='badge badge-secondary check-lable'>" + assembler + "</div>");
        chArray[id] = assembler;
        $(event.target).css("background-color", "#0d6efd")
    } else {
        $("#" + id + "+ div").remove();
        chArray[id] = '';
        $(event.target).css("background-color", "white")
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
}

$('#date').click(function (e) {
    e.preventDefault();
    toggleAll();
});

function toggleQc(id, qc_name) {
    if (qc_name != '') {
        chArray[id] = qc_name;
    } else {
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
    $('#assembler').val(assembler);
    $('#input_data').val(chArray.toString());
    if ($(e.target).is(":checked")) {
        log += getDateTime() + " " + assembler + " checked " + $(this).closest("tr").find('th').text() + ";";
    }
    $('#input_log').val(log);
});

$("select.review").change(function (e) {
    e.preventDefault();
    id = this.id;
    curent_th = $(this).closest("tr").find('th').text();
    var option = $(this).children("option:selected");
    var name = option.val();
    if (option.val() != "0") {
        var password = '';
        if (name != assembler) {
            password = prompt(name + "- please enter your Password.", "");
        }
        $.post("/users/get_verify",
            {
                name: name,
                password: password
            },
            function (verify) {
                if (verify == true) {
                    option.val(name);
                    toggleQc(id, name);
                    $('#input_qc').val(name);
                    log += getDateTime() + " QC " + name + " checked " + curent_th + ";";
                    $('#input_log').val(log);
                    $('#input_data').val(chArray.toString());
                } else {
                    option.val('Select');
                    toggleQc(id, "Select");
                    alert("Password error!" + verify);
                    $('#input_data').val(chArray.toString());
                }
            });
    } else {
        option.val('Select');
        toggleQc(id, "Select");
        $('#input_data').val(chArray.toString());
    }

});

$('textarea#note').change(function () {
    $('#input_note').val($(this).val());
});

$('input.input').change(function (e) {
    chArray[this.id] = $(this).val().replace(",", "|");
    log += getDateTime() + assembler + " inserted " + $(this).val() + ";";
    $('#input_log').val(log);
    $('#input_data').val(chArray.toString());
    updateProgress();
});

$('#result').click(function () {
    //alert(getDateTime());
});

function getDateTime() {
    var now = new Date();
    const ye = new Intl.DateTimeFormat('en', { year: '2-digit' }).format(now);
    const mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(now);
    const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(now);
    const H = (now.getHours() < 10 ? '0' : '') + now.getHours();
    const M = (now.getMinutes() < 10 ? '0' : '') + now.getMinutes();
    // return `${da}/${mo}/${ye} ${H}:${M}`;
    return `${da}/${mo}/${ye}`;
}

$('#ajax-form-qc').submit(function (event) {
    event.preventDefault();
    var formData = $('#ajax-form-qc').serialize();
    $.ajax({
        type: 'POST',
        url: $('#ajax-form-qc').attr('action'),
        data: formData
    }).done(function (response) {
        if (!response.startsWith('ERROR')) {
            show_message_success(response)
        } else {
            show_message_error(response)
        }
    }).fail(function () {
        show_message_error('Oops! An error occured and your message could not be sent.')
    });
    location.reload();
});

$('#ajax-form-scans').submit(function (event) {
    event.preventDefault();
    var formData = $('#ajax-form-scans').serialize();
    $.ajax({
        type: 'POST',
        url: $('#ajax-form-scans').attr('action'),
        data: formData
    }).done(function (response) {
        if (!response.startsWith('ERROR')) {
            show_message_success(response)
        } else {
            show_message_error(response)
        }
    }).fail(function () {
        show_message_error('Oops! An error occured and your message could not be sent.')
    });
});

//CONTEXTMENU START
$(".verify").bind('contextmenu', function (e) {
    var id = this.id;
    $("#checkbox_id").val(id);

    var top = e.pageY + 20;
    var left = e.pageX - 50;

    // Show contextmenu
    $(".context-menu").toggle(100).css({
        top: top + "px",
        left: left + "px"
    });
    return false;
});

// Clicked context-menu item
$('.context-menu li').click(function () {
    var checkbox_id = $('#checkbox_id').val();
    var className = $(this).find("span:nth-child(1)").attr("class");
    if (className == "checkbox_yes") {
        $("#" + checkbox_id).prop("checked", true);
        $("#" + checkbox_id).prop("indeterminate", false).css("background-color", "#0d6efd");
        $("#" + checkbox_id).after("<div class='badge badge-secondary check-lable'>" + assembler + "</div>");
        log += getDateTime() + " " + assembler + " checked yes " + $("#" + checkbox_id).closest("tr").find('th').text() + ";";
        chArray[checkbox_id] = assembler;
    }
    if (className == "checkbox_no") {
        $("#" + checkbox_id).prop("checked", true);
        $("#" + checkbox_id).prop("indeterminate", true).css("background-color", "#ff9595");
        $("#" + checkbox_id).after("<div class='badge badge-secondary check-lable'>" + assembler + "</div>");
        chArray[checkbox_id] = "!" + assembler;
        log += getDateTime() + " " + assembler + " checked no " + $("#" + checkbox_id).closest("tr").find('th').text() + ";";
    }
    $('#input_log').val(log);
    $('#input_data').val(chArray.toString());
    updateProgress();
    $(".context-menu").hide();
});

$(document).bind('contextmenu click', function () {
    $(".context-menu").hide();
});

$('.context-menu').bind('contextmenu', function () {
    return false;
});

//KEYBOARD BIDINGS
document.onkeydown = function (e) {
    if (e.ctrlKey && e.key == "s") { //ctrl + S
        e.preventDefault();
        $("#save").click();
    } else if (e.ctrlKey && e.key == "a") { //ctrl+a
        e.preventDefault();
        toggleAll();
    } else if (e.key == "Enter") {
        e.preventDefault();
        var focused = $(':focus')
        var id = focused.parent().parent().attr('id');
        if (id >= 0) {
            id = parseInt(id) + 1;
        }
        $('tr[id^=' + id + '] input:eq(0)').focus();
    }
};
