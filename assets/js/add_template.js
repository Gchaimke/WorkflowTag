$(document).ready(function () {
    var arr = clients[$("#select_client").first().val()].split(",");
    $.each(arr, function (_index, project) {
        if (project.trim() == curent_project) {
            $("#select_project").append('<option selected>' + project.trim() +'</option>');
        } else {
            $("#select_project").append('<option>' + project.trim() + '</option>');
        }
    });
});

$("#select_client").change(function () {
    var arr = clients[$(this).val()].split(",");
    $("#select_project").empty();
    $.each(arr, function (_index, project) {
        $("#select_project").append('<option>' + project + '</option>');
    });
});
