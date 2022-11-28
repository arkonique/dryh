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

$stmt=$conn->prepare('SELECT * FROM images');
$stmt->execute();

$htmlstr="";
$result=$stmt->get_result();
if ($result->num_rows!=0){
  while ($row = $result->fetch_assoc()){
    $htmlstr=$htmlstr."<label class='images'><input type='radio' name='sendimg' value='{$row['name']}' checked> <span class='g-img-wrapper'><img class='g-img' src={$row['name']}></span></label>";
  }}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/screen.css">
    <link rel="stylesheet" href="../css/gallery.css">
    <title></title>
  </head>
  <body>
    <h1>Image Gallery</h1>
      <article class="gallery">
        <?php echo $htmlstr; ?>
      </article>
      <label><input type="checkbox" name="show" value="1"><span class="showbtn">Show</span><p id="statecheck"><i class="fa-solid fa-circle-check"></i> Live</p></label>
    <script type="text/javascript" async>

      let show=setInterval(()=>{
        state=(document.querySelector('input[name=show]').checked)?1:0;
        name=document.querySelector('input[name="sendimg"]:checked').value;
        fetch( `saveimg.php/?state=${state}&img=${name}` )
    },1000)

    </script>
  </body>
</html>
