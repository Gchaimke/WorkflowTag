const video = document.getElementById('video');
const button = document.getElementById('select_camera');
const select = document.getElementById('select');
let currentStream;

function stopMediaTracks(stream) {
  stream.getTracks().forEach(track => {
    track.stop();
  });
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
  navigator.mediaDevices
    .getUserMedia(constraints)
    .then(stream => {
      currentStream = stream;
      video.srcObject = stream;
      return navigator.mediaDevices.enumerateDevices();
    })
    .then(gotDevices)
    .catch(error => {
      console.error(error);
    });
});

navigator.mediaDevices.enumerateDevices().then(gotDevices);

video.addEventListener("click", function () {
  var canvas = document.getElementById('canvas');
  var context = canvas.getContext('2d');
  $(".video-frame").toggle();
  context.drawImage(video, 0, 0, 1920, 1080);
  stopMediaTracks(currentStream);
  dataURL = canvas.toDataURL();
  $.post("save_photo", {
    data: dataURL,
    pr: GetURLParameter('pr'),
    sn: GetURLParameter('sn'),
    num: pictueCount
  }).done(function (o) {
    console.log('photo saved to server.');
    $("#photo-stock").append('<span id="' + GetURLParameter('sn') + '_' + pictueCount + '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">delete</span><img id="' + GetURLParameter('sn') + '_' + pictueCount + '"src="/Production/' + GetURLParameter('pr')  + '/' + GetURLParameter('sn') + '/' + GetURLParameter('sn') + '_' + pictueCount + '.png' + '" class="respondCanvas" >');
    // If you want the file to be visible in the browser 
    // - please modify the callback in javascript. All you
    // need is to return the url to the file, you just saved 
    // and than put the image in your browser.
    pictueCount++;
  });
  
});

function delPhoto(id) {
  $.post("delete_photo", {
    photo: '/Production/' + GetURLParameter('pr')  + '/' + GetURLParameter('sn') + '/' + id + '.png'
  }).done(function (o) {
    console.log('photo deleted from the server.');
    $('[id^='+id+']').remove();
  });
}

// Trigger photo take
document.getElementById("snap").addEventListener("click", function () {
  $(".video-frame").toggle();
});

$("#close_camera").click(function () {
  stopMediaTracks(currentStream);
  $(".video-frame").toggle();
});

