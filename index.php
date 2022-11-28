<?php
session_start();
if (isset($_SESSION['token'])){
  ?>
  <script type="text/javascript">
  window.location = "sheet";
</script>
  <?php
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" type="text/css" href="css/csshake.min.css">
    <link rel="icon" href="https://dnd5echarsheet.com/dryh/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/login.css">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Don't Rest Your Head Login</title>
  </head>
  <body>
    <img src="img/login_bg.jpg" alt="">
    <img src="img/login_bg.jpg" class="flicker">
    <img src="img/login_bg_top.png" class="flicker">
    <form action="" method="post" autocomplete="off">
      <h1 class="shake-crazy shake-freeze">DON'T REST YOUR HEAD</h1>
      <label for="player">Username</label><input type="text" name="player" value="" required>
      <label for="charname">Character name</label><input type="text" name="charname" value="" required>
      <button type="submit" name="submitform" class='shake shake-constant shake-constant--hover'>Enter</button>
    </form>
  </body>
</html>

<?php
$config = parse_ini_file('../../dryh_config.ini');
$conn = new mysqli($config['server'],$config['uname'],$config['pw'],$config['db']);

if (isset($_POST['submitform'])){
$u=$_POST['player'];
$c=$_POST['charname'];
$t=uniqid('user_',true);
$_SESSION['user']=$u;
$_SESSION['char']=$c;
$_SESSION['token']=$t;

$stmt=$conn->prepare("SELECT * FROM chars WHERE charname=? AND player=?");
$stmt->bind_param('ss',$c,$u);
$stmt->execute();

$result=$stmt->get_result();
if ($result->num_rows==0){ //set token in all the tables
  $stmt2=$conn->prepare("INSERT INTO chars (charname,player,uid) VALUES (?,?,?)");
  $stmt2->bind_param('sss',$c,$u,$t);
  $stmt2->execute();

  $stmt3=$conn->prepare("INSERT INTO rolls (uid,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
  $stmt3->execute([$t,0,0,0,0,0,0,0,0,0,0,0,0]);
}
else {
  while ($row = $result->fetch_assoc()){
    $_SESSION['token']=$row['uid'];
  }
}

?>
<script type="text/javascript">
window.location = "sheet";
</script>
<?php

    }

 ?>
