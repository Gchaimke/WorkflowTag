function showLog(log_data) {
    if (log_data != '') {
        log_arr = log_data.split(';')
        $("#show-log").show();
        $("#show-log .list-group").empty();
        log_arr.forEach(element => {
            if (element != ''){
                if (~element.indexOf("QC")){
                    $("#show-log .list-group").append("<li class='list-group-item list-group-item-warning'>" + element + "</li>");
                }else{
                    $("#show-log .list-group").append("<li class='list-group-item list-group-item-info'>" + element + "</li>");
                }
            }
        });
    }
}

$(".close").click(function () {
    $("#show-log").hide();
});