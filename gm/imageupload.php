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
    <link rel="stylesheet" href="../css/images.css">
    <title></title>
  </head>
  <body>
    <h1>Upload images</h1>
    <form class="" name="imgform" action="" method="post" enctype="multipart/form-data" >
          <label>
            <i class="fa-regular fa-image"></i>
            <span>Choose file to upload</span>
            <img id="portrait-img" src="" alt="">
            <input type="file" name="image" value="" onchange="readURL(this)">
          </label>
        <button type="submit" name="button"><i class="fa-solid fa-upload"></i></button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    function getAverageRGB(imgEl) {

        var blockSize = 5, // only visit every 5 pixels
            defaultRGB = {r:0,g:0,b:0}, // for non-supporting envs
            canvas = document.createElement('canvas'),
            context = canvas.getContext && canvas.getContext('2d'),
            data, width, height,
            i = -4,
            length,
            rgb = {r:0,g:0,b:0},
            count = 0;

        if (!context) {
            return defaultRGB;
        }

        height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
        width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;

        context.drawImage(imgEl, 0, 0);

        try {
            data = context.getImageData(0, 0, width, height);
        } catch(e) {
            /* security error, img on diff domain */;
            return defaultRGB;
        }

        length = data.data.length;

        while ( (i += blockSize * 4) < length ) {
            ++count;
            rgb.r += data.data[i];
            rgb.g += data.data[i+1];
            rgb.b += data.data[i+2];
        }

        // ~~ used to floor values
        rgb.r = ~~(rgb.r/count);
        rgb.g = ~~(rgb.g/count);
        rgb.b = ~~(rgb.b/count);

        return rgb;

    }





    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('.fa-image').css('display','none');
          $('label span').css('display','none');
          $('#portrait-img').attr('src', e.target.result);
          var rgb = getAverageRGB(document.getElementById('portrait-img'));
          $('label').css('background','rgb('+rgb.r+','+rgb.g+','+rgb.b+')')
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
    </script>
  </body>
</html>


<?php
if (isset($_POST['button'])) {
  $target_dir = "../img/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  $uploadOk = 0;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "<p class='emessage'>File is not an image</p>";
    $uploadOk = 0;
  }
  if (file_exists($target_file)) {
    echo "<p class='emessage'>File already exists. Please use a different filename</p>";
    $uploadOk = 0;
  }
  if ($uploadOk == 0) {
  } else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      echo "<p class='message'>File uploaded successfully.</p>";
    } else {
    }
  }

$stmt=$conn->prepare("INSERT INTO images (name) VALUES (?)");
$stmt->execute([$target_file]);

}
?>
