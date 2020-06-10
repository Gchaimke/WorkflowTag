function snapPhoto() {
  //var preview = document.querySelector('#preview');
  var files = document.querySelector('input[type=file]').files;
  function readAndPreview(file) {
    // Make sure `file.name` matches our extensions criteria
    if (/\.(jpe?g|jpeg|gif)$/i.test(file.name)) {
      var reader = new FileReader();
      reader.addEventListener("load", function () {
        var image = new Image();
        image.title = file.name;
        image.src = this.result;
        //preview.appendChild(image);
        saveToServer(this.result)
      }, false);
      reader.readAsDataURL(file);
    }
  }

  if (files) {
    [].forEach.call(files, readAndPreview);
  }
}

function saveToServer(file) {
  $.post("/production/save_photo", {
    data: file,
    pr: pr,
    sn: sn,
    num: photoCount
  }).done(function (o) {
    console.log('photo saved to server.');
    $("#photo-stock").append('<span id="' + sn + '_' + photoCount +
      '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">delete ' +
      sn + '_' + photoCount + '</span><img id="' +
      sn + '_' + photoCount + '"src="/Uploads/' + pr + '/' + sn +
      '/' + sn + '_' + photoCount + '.jpeg' + '" class="respondCanvas" >');
    photoCount++;

  });
}

function delPhoto(id) {
  var r = confirm("Delete Photo with id: " + id + "?");
  if (r == true) {
    $.post("/production/delete_photo", {
      photo: '/Uploads/' + pr + '/' + sn + '/' + id + '.jpeg'
    }).done(function (o) {
      console.log('photo deleted from the server.');
      $('[id^=' + id + ']').remove();
    });
  }
}




