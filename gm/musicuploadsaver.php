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


  // code...
$audioFileType = pathinfo(basename($_FILES["music"]["name"]),PATHINFO_EXTENSION);
$target_dir = "../music/";
$target_file = $target_dir . $_POST['fname'] . "." . $audioFileType;
$uploadOk = 1;
// Check if file already exists
if (file_exists($target_file)) {
    echo "<p class='emessage'>Sorry, file already exists.</p>";
    $uploadOk = 0;
}
// Check file size in bytes
if ($_FILES["music"]["size"] > 5000000000) {
    echo "<p class='emessage'>Sorry, your file is too large.</p>";
    $uploadOk = 0;
}

// Allow certain file formats only .wav, .mp3, .wma, and .m4a files can be uploaded
if($audioFileType != "wav" && $audioFileType != "mp3" && $audioFileType != "wma"
&& $audioFileType != "m4a" ) {
    echo "<p class='emessage'>Sorry, only wav, mp3, wma & m4a files are allowed.</p>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<p class='emessage'>Sorry, your file was not uploaded.</p>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["music"]["tmp_name"], $target_file)) {
        #$stmt=$con->prepare("INSERT INTO ");
        echo "<p class='message'>The file ". basename( $_FILES["music"]["name"]). " has been uploaded as " . $_POST['fname'] . "." . $audioFileType . ".</p>";
        $data[0]=$_POST['fname'].".".$audioFileType;

        $data[1]=$_POST['tag'];

        $v=(isset($_POST['category']))?$_POST['category']:[""];
        $data[2]="";
        foreach ($v as $key => $value) {
          $data[2]=$data[2].$value.",";
        }
        $data[2]=rtrim($data[2],",");


        $stmt=$conn->prepare("INSERT INTO music (name,tag,categories) VALUES (?,?,?)");
        $stmt->execute($data);

    } else {
        echo "<p class='emessage'>Sorry, there was an error uploading your file.</p>";
    }
}

?>
