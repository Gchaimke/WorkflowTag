const video = document.getElementById('video');
const button = document.getElementById('button');
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

var pictueCount = 0;
video.addEventListener("click", function () {
  $("#photo-stock").append('<span id="delete' + pictueCount + '" onclick="delPhoto(' + pictueCount +
    ',this.id)" class="btn btn-danger delete-photo">delete</span><canvas id="canvas' + pictueCount +
    '" class="respondCanvas" width="1920" height="1080"></canvas>');
  var canvas = document.getElementById('canvas' + pictueCount);
  var context = canvas.getContext('2d');
  $(".video-frame").toggle();
  context.drawImage(video, 0, 0, 1920, 1080);
  stopMediaTracks(currentStream);
  pictueCount++;
});

function delPhoto(id, cur) {
  $('#canvas' + id).remove();
  $('#' + cur).remove();
}

// Trigger photo take
document.getElementById("snap").addEventListener("click", function () {
  $(".video-frame").toggle();
});

$("#save").click(function () {
  var canvas = document.getElementById('canvas0');
  dataURL = canvas.toDataURL();
  $.post("save_photo", { data: dataURL }).done(function (o) {
    console.log('photo saved to server.');
    // If you want the file to be visible in the browser 
    // - please modify the callback in javascript. All you
    // need is to return the url to the file, you just saved 
    // and than put the image in your browser.
  });
});