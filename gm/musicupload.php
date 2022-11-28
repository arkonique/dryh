<?php
$config = parse_ini_file('../../../dryh_config.ini');
$conn = new mysqli($config['server'],$config['uname'],$config['pw'],$config['db']);
session_start();
if (!isset($_SESSION['uname'])){
  ?>
  <script type="text/javascript">
  window.location = "./";
</script>
  <?php
}

?><!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/screen.css">
    <link rel="stylesheet" href="../css/music.css">
    <title></title>
  </head>
  <body>
    <form class="" name="musicform" action="" method="post" enctype="multipart/form-data" >
      <h1>Upload music</h1>
      <label id="flabel"><i class="fa-solid fa-music"></i> Choose file <input type="file" name="music" id="music" value="" onchange="readName()"></label>
      <label for="fname">New filename (without extension):</label> <input type="text" name="fname" value="">
      <label for="tag">Comma separated tags (e.g. horror, shopping, finalboss, etc.): </label> <input type="text" name="tag" value="" placeholder="">
      <div class="cats">
        <h2>Categories</h2>
      <label>
        <input type="checkbox" name="category[]" value="atmospheric">
        <span class="checkmark"></span><span id="atm">Atmospheric</span>
      </label>
      <label>
        <input type="checkbox" name="category[]" value="chase">
        <span class="checkmark"></span><span id="sha">Chase</span>
      </label>
      <label>
        <input type="checkbox" name="category[]" value="combat">
        <span class="checkmark"></span><span id="com">Combat</span>
      </label>
      <label>
        <input type="checkbox" name="category[]" value="exploration">
        <span class="checkmark"></span><span id="exp">Exploration</span>
      </label>
    </div>

      <button type="button" name="button" onclick="uploadFile()">Upload</button>
        <progress id="progressBar" value="0" max="100"></progress>
        <h3 id="status"></h3>
    </form>
    <script type="text/javascript">
      function readName(){
        var file = document.forms['musicform']['music'].files[0];
        document.querySelector('input[name="fname"]').value=file.name;
      }

      function _(el) {
  return document.getElementById(el);
}

function uploadFile() {
  var file = _("music").files[0];
  // alert(file.name+" | "+file.size+" | "+file.type);
  var formdata = new FormData();
  formdata.append("music",file);
  formdata.append("fname", document.querySelector('input[name="fname"]').value);
  formdata.append("tag", document.querySelector('input[name="tag"]').value);
  for (var v of [...document.querySelectorAll('input[name="category[]"]:checked')]) {
    formdata.append('category[]',v.value);
  }


  var ajax = new XMLHttpRequest();
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "musicuploadsaver.php");
  ajax.send(formdata);
}

function progressHandler(event) {
  var percent = (event.loaded / event.total) * 100;
  _("progressBar").value = Math.round(percent);
  _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
}

function completeHandler(event) {
  _("status").innerHTML = event.target.responseText;
  _("progressBar").value = 0; //wil clear progress bar after successful upload
}

function errorHandler(event) {
  _("status").innerHTML = "Upload Failed";
}

function abortHandler(event) {
  _("status").innerHTML = "Upload Aborted";
}
    </script>
  </body>
</html>
