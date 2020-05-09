const video = document.getElementById('video');
const button = document.getElementById('select_camera');
const select = document.getElementById('select');
let currentStream;

function stopMediaTracks(stream) {
  if (typeof stream !== 'undefined') {
    stream.getTracks().forEach(track => {
      track.stop();
    });
  }
}

function gotDevices(mediaDevices) {
  select.innerHTML = '';
  select.appendChild(document.createElement('option'));
  let count = 1;
  mediaDevices.forEach(mediaDevice => {
    if (mediaDevice.kind === 'videoinput') {
      const option = document.createElement('option');
      option.value = mediaDevice.deviceId;
      const label = mediaDevice.label || `Camera ${count++}`;
      const textNode = document.createTextNode(label);
      option.appendChild(textNode);
      select.appendChild(option);
    }
  });
}

button.addEventListener('click', event => {
  if (typeof currentStream !== 'undefined') {
    stopMediaTracks(currentStream);
  }
  const videoConstraints = {};
  if (select.value === '') {
    videoConstraints.facingMode = 'environment';
  } else {
    videoConstraints.deviceId = { exact: select.value };
  }
  const constraints = {
    video: videoConstraints,
    audio: false
  };
  if (typeof navigator.mediaDevices !== 'undefined') {
    navigator.mediaDevices.getUserMedia(constraints).then(stream => {
      currentStream = stream;
      video.srcObject = stream;
      return navigator.mediaDevices.enumerateDevices();
    })
      .then(gotDevices)
      .catch(error => {
        console.error(error);
      });
  }
});

if (typeof navigator.mediaDevices !== 'undefined') {
  navigator.mediaDevices.enumerateDevices().then(gotDevices);
}

video.addEventListener("click", function () {
  var canvas = document.getElementById('canvas');
  var context = canvas.getContext('2d');
  $(".video-frame").toggle();
  context.drawImage(video, 0, 0, 1920, 1080);
  stopMediaTracks(currentStream);
  dataURL = canvas.toDataURL('image/png', 0.1);
  $.post("/production/save_photo", {
    data: dataURL,
    pr: pr,
    sn: sn,
    num: photoCount
  }).done(function (o) {
    console.log('photo saved to server.');
    $("#photo-stock").append('<span id="' + sn + '_' + photoCount +
      '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">delete</span><img id="' +
      sn + '_' + photoCount + '"src="/Uploads/' + pr + '/' + sn +
      '/' + sn + '_' + photoCount + '.png' + '" class="respondCanvas" >');
    photoCount++;

  });

});

function delPhoto(id) {
  var r = confirm("Delete Photo with id: " + id + "?");
  if (r == true) {
    $.post("/production/delete_photo", {
      photo: '/Uploads/' + pr + '/' + sn + '/' + id + '.png'
    }).done(function (o) {
      console.log('photo deleted from the server.');
      $('[id^=' + id + ']').remove();
    });
  }
}

// Trigger photo take
$("#snap").click(function () {
  $(".video-frame").toggle();
});

$("#close_camera").click(function () {
  stopMediaTracks(currentStream);
  $(".video-frame").toggle();
});

