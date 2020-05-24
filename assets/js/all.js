function showLog(log_data,serial) {
    if (log_data != '') {
        log_arr = log_data.split(';')
        $("#show-log").show();
        $("#show-log .list-group").empty();
        $("#serial-header").text(serial);
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

dragElement(document.getElementById("show-log"));

function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById("show-log-header")) {
      // if present, the header is where you move the DIV from:
      document.getElementById("show-log-header").onmousedown = dragMouseDown;
    } else {
      // otherwise, move the DIV from anywhere inside the DIV:
      elmnt.onmousedown = dragMouseDown;
    }
  
    function dragMouseDown(e) {
      e = e || window.event;
      e.preventDefault();
      // get the mouse cursor position at startup:
      pos3 = e.clientX;
      pos4 = e.clientY;
      document.onmouseup = closeDragElement;
      // call a function whenever the cursor moves:
      document.onmousemove = elementDrag;
    }
  
    function elementDrag(e) {
      e = e || window.event;
      e.preventDefault();
      // calculate the new cursor position:
      pos1 = pos3 - e.clientX;
      pos2 = pos4 - e.clientY;
      pos3 = e.clientX;
      pos4 = e.clientY;
      // set the element's new position:
      elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
      elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }
  
    function closeDragElement() {
      // stop moving when mouse button is released:
      document.onmouseup = null;
      document.onmousemove = null;
    }
  }