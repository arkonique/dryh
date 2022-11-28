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



$stmt=$conn->prepare("SELECT * FROM chars");
$stmt->execute();
$result=$stmt->get_result();
if ($result->num_rows!=0){
  while ($row = $result->fetch_assoc()){
    $players[$row['uid']]=$row['player'];
  }
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta http-equiv="refresh" content="30">
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/screen.css">
    <title></title>
  </head>
  <body>
    <form class="" action="" method="post">
      <?php
        $stmt=$conn->prepare("SELECT * FROM coins");
        $stmt->execute();
        $result=$stmt->get_result();
        if ($result->num_rows!=0){
          while ($row = $result->fetch_assoc()){
            if ($row['lastuse']!=''){
              echo "<span id='user'>Last hope user: {$players[$row['lastuse']]}</span><br>";
            }
            else {
              echo "<span id='user'>Last hope user: -</span><br>";
            }
            $r['despair']=$row['despair'];
            $r['hope']=$row['hope'];
            echo "<label for='despair'>Despair: </label><input type='number' name='despair' value='{$row['despair']}'><br>";
            echo "<span id='hope1'>Current Hope: </span><span>{$row['hope']}</span><br>";
          }
        }
        else {
          $r['despair']=0;
          $r['hope']=0;
          echo "<label for='despair'>Despair: </label><input type='number' name='despair' value='0'><br>";
          echo "<span id='hope1'>Current Hope: </span><span>0</span><br>";
        }
      ?>
      <button type="submit" name="coinupdate">Save</button>
    </form>
  </body>
</html>
<?php
  if (isset($_POST['coinupdate'])) {
    if ($_POST['despair']<$r['despair']){
      $h=intval($r['hope'])+intval($r['despair'])-intval($_POST['despair']);
    }
    else {
      $h=$r['hope'];
    }
    $stmt=$conn->prepare("UPDATE coins SET despair=?, hope=? WHERE id=1");
    $stmt->execute([$_POST['despair'],$h]);
?>
<script type="text/javascript">
  window.location="./despair";
</script>
<?php
  }
?>
