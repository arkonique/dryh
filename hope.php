<?php
if( isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] != 'iframe' ) {
?>
<script type="text/javascript">
  window.location="./";
</script>
<?php
}
$config = parse_ini_file('../../dryh_config.ini');
$conn = new mysqli($config['server'],$config['uname'],$config['pw'],$config['db']);

session_start();

if (!isset($_SESSION['token'])){
  ?>
  <script type="text/javascript">
  window.location = "./";
</script>
  <?php
}
else {
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link rel="stylesheet" href="css/hope.css">
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <title></title>
  </head>
  <body>
    <form class="" action="" method="post">
      <?php
        $token=$_SESSION['token'];
        $stmt=$conn->prepare("SELECT * FROM coins");
        $stmt->execute();
        $result=$stmt->get_result();
        if ($result->num_rows!=0){
          while ($row = $result->fetch_assoc()){
            $r=$row['hope'];
            echo "<h1>USE HOPE</h1>";
            echo "<p id='pool'>Current pool: </p>";
            echo "<input type='range' min=0 max={$row['hope']} value=0 name='hope' id='hope'>";
            echo "<p id='hope-p'>Using:</p>";
          }
        }
      }
      ?>
      <button type="submit" name="usehope">Use</button>
    </form>
  <script type="text/javascript">

  var slider1 = document.getElementById("hope");
  var output1 = document.getElementById("hope-p");
  slider1.oninput = function() {
    html=`Using: `;
    for (var i = 0; i < this.value; i++) {
      html=`${html}<i class="fa-solid fa-sun"></i>`
    }
    output1.innerHTML = html;
  }
  html=`Current pool: `
  for (var i = 0; i < <?php echo $r;?>; i++) {
    html=`${html}<i class="fa-solid fa-sun"></i>`
  }
  var output2=document.getElementById('pool');
  output2.innerHTML=html
  </script>
  </body>
</html>
<?php
if (isset($_POST['usehope'])) {
  $h=$r-$_POST['hope'];
  $stmt=$conn->prepare("UPDATE coins SET hope=?, lastuse=? WHERE id=1");
  $stmt->execute([$h,$token]);
  echo "<script>window.location='hope'</script>";
}
?>
