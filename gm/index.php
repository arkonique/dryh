<?php
session_start();
if (isset($_SESSION['uname'])){
  ?>
  <script type="text/javascript">
  window.location = "screen";
</script>
  <?php
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/gmlogin.css">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Don't Rest Your Head GM Login</title>
  </head>
  <body>
    <form action="" method="post" autocomplete="off">
      <label for="player">Username</label><input type="text" name="player" value="">
      <label for="charname">Password</label><input type="password" name="password" value="">
      <label for="access">Access Code</label><input type="password" name="access" value="">
      <button type="submit" name="submitform">Login</button>
    </form>
  </body>
</html>

<?php
$config = parse_ini_file('../../../dryh_config.ini');
$conn = new mysqli($config['server'],$config['uname'],$config['pw'],$config['db']);

if (isset($_POST['submitform'])){
  if ($_POST['access']!='a31452') {
    echo "<script>alert('Wrong access code')</script>";
  }
  else{

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
$u=$_POST['player'];
//$t=uniqid('user_',true);
$_SESSION['uname']=$u;

$stmt=$conn->prepare("SELECT * FROM gms WHERE uname=?");
$stmt->bind_param('s',$u);
$stmt->execute();

$result=$stmt->get_result();
if ($result->num_rows==0){ //set token in all the tables
  $stmt2=$conn->prepare("INSERT INTO gms (uname,password) VALUES (?,?)");
  $stmt2->bind_param('ss',$u,$hash);
  $stmt2->execute();
  ?>
  <script type="text/javascript">
  window.location = "screen";
  </script>
  <?php
}
else {
  while ($row = $result->fetch_assoc()){
    if (password_verify($_POST['password'],$row['password'])) {
?>
<script type="text/javascript">
window.location = "screen";
</script>
<?php
    }
    else{
?>
<script type="text/javascript">
alert("Incorrect Password");
</script>
<?php
    }
  }
}

    }
  }

 ?>
