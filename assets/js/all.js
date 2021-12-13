var count = 0;
function showLog(log_data, serial) {
    if (log_data != '') {
        log_arr = log_data.split(';')
        $("#show-log").show();
        $("#show-log .list-group").empty();
        $("#serial-header").text(serial);
        log_arr.forEach(element => {
            if (element != '') {
                if (~element.indexOf("QC")) {
                    $("#show-log .list-group").append("<li class='list-group-item list-group-item-warning'>" + element + "</li>");
                } else if (~element.indexOf(" no ")) {
                    $("#show-log .list-group").append("<li class='list-group-item list-group-item-danger'>" + element + "</li>");
                } else {
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
    if (elmnt != null) {
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
}

$('input[type="files"]').change(function (e) {
    var fileName = e.target.files[0].name;
    alert('The file "' + fileName + '" has been selected.');
});

function snapLogo() {
    var logo_path = document.getElementById('logo_path');
    var logo_img = document.getElementById('logo_img');
    var files = document.querySelector('input[type=file]').files;

    function readAndPreview(file) {
        // Make sure `file.name` matches our extensions criteria
        ext = file.name.substr((file.name.lastIndexOf('.') + 1));
        if (/\.(jpe?g|png|gif)$/i.test(file.name)) {
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                saveLogoToServer(this.result);
                sleep(2000);
                var image = new Image();
                image.title = file.name;
                image.src = this.result;
                logo_path.value = "/Uploads/Clients/" + client + "_logo." + ext;
                logo_img.src = logo_path.value;
            }, false);
            reader.readAsDataURL(file);
        }
    }
    if (files) {
        [].forEach.call(files, readAndPreview);
    }
}

function saveLogoToServer(file) {
    $.post("/clients/logo_upload", {
        data: file,
        client: client,
        ext: ext
    }).done(function (o) {
        console.log('photo saved to server.');
        console.log(o);
    });
}



$('#ajax-form').submit(function (event) {
    // Stop the browser from submitting the form.
    event.preventDefault();
    var formData = $('#ajax-form').serialize();
    var new_location = $('#form-messages').attr('data-url');
    $.ajax({
        type: 'POST',
        url: $('#ajax-form').attr('action'),
        data: formData
    }).done(function (response) {
        if (!response.startsWith('ERROR')) {
            show_message_success(response)
            if (typeof new_location !== 'undefined') {
                var new_id = response.split(":")[0];
                setTimeout(function () { window.location.replace(new_location + new_id); }, 2000);
            }
        } else {
            show_message_error(response)
        }
    }).fail(function () {
        show_message_error('Oops! An error occured and your message could not be sent.')
    });

});

function show_message_success(response) {
    $('#form-messages').removeClass('alert-danger');
    $('#form-messages').addClass('alert-success');
    $('#form-messages').text(response).fadeIn(1000).delay(3000).fadeOut(1000); //show message
}

function show_message_error(response) {
    $('#form-messages').removeClass('alert-success');
    $('#form-messages').addClass('alert-danger');
    $('#form-messages').text(response).fadeIn(1000).delay(3000).fadeOut(1000); //show message
}

function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
        currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}

function snapPhoto() {
    var files = document.querySelector('input[name=photos]').files;
    console.log(files)
    function readAndPreview(file) {
        if (/\.(jpe?g|jpeg|gif|png)$/i.test(file.name)) {
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                savePhotoToServer(this.result);
                var image = new Image();
                image.title = file.name;
                image.src = this.result;
                photoCount++;
                $("#picrures_count").val(photoCount);
            }, false);
            reader.readAsDataURL(file);
        }

    }
    if (files) {
        [].forEach.call(files, readAndPreview);
    }
}

function savePhotoToServer(file) {
    $.post("/production/save_photo", {
        data: file,
        serial: serial,
        num: photoCount,
        working_dir: working_dir
    }).done(function (out) {
        var photo_id = out.split("/")[4].replace(".jpeg", "").replace(".png", ""); //get photo id
        $("#photo-stock").append('<span id="' + photo_id + '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo fa fa-trash"> ' +
            photo_id + '</span><img id="' + photo_id + '"src="/' + out + '" class="respondCanvas" >');
        $('#form-messages').addClass('alert-success');
        $("#save").trigger("click");
        console.log(out + " Uploaded");
    });
}

function delPhoto(id) {
    var photo = $('img[id^=' + id + ']').attr('src');
    var r = confirm("Delete " + photo + "?");
    if (r == true) {
        $.post("/production/delete_photo", {
            photo: photo
        }).done(function (out) {
            $('#form-messages').addClass('alert-success');
            $('[id^=' + id + ']').remove();
            photoCount--;
            $("#picrures_count").val(photoCount);
            $("#save").trigger("click");
            console.log(out);
        });

    }
}

function delFile(id) {
    var file = $("#" + id).attr('data-file');
    var r = confirm("Delete file " + file + "?");
    if (r == true) {
        $.post("/forms/delete_file", {
            file: file
        }).done(function (out) {
            $('#form-messages').addClass('alert-success');
            $('[id^=' + id + ']').remove();
            $("#save").trigger("click");
            location.reload();
        });

    }
}

if ($('#nav_main_category_data').length) {
    var nav_to = $('#nav_main_category_data').attr('data-url');
    var nav_name = $('#nav_main_category_data').attr('data-url-name');
    $('#nav_main_category').attr('href', nav_to).text(nav_name).removeAttr('hidden');
}
